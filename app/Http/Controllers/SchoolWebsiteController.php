<?php

namespace App\Http\Controllers;

use App\Mail\AdmissionAppliedMail;
use App\Models\AdmissionApplication;
use App\Models\AdmissionDocument;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\School;
use App\Models\SchoolBranch;
use App\Models\SchoolClass;
use App\Models\StaffJobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SchoolWebsiteController extends Controller
{
    /**
     * Resolve school by slug or subdomain.
     */
    protected function getSchool(string $slug): School
    {
        return School::where('slug', $slug)
            ->orWhere('subdomain', $slug)
            ->firstOrFail();
    }

    /**
     * Show the public school website.
     */
    public function show(string $slug)
    {
        $school = $this->getSchool($slug);

        $classes = SchoolClass::where('school_id', $school->id)
            ->where('status', 'active')
            ->with(['arms' => function ($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('name')
            ->get();

        $announcements = Announcement::where('school_id', $school->id)
            ->where('status', 'active')
            ->orderBy('announced_at', 'desc')
            ->limit(4)
            ->get();

        $events = Event::where('school_id', $school->id)
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();

        $branches = SchoolBranch::where('school_id', $school->id)
            ->where('status', 'active')
            ->get();

        return view('school-website.show', compact('school', 'classes', 'announcements', 'events', 'branches'));
    }

    /**
     * Show the online admission application form for this school.
     */
    public function apply(string $slug)
    {
        $school = $this->getSchool($slug);

        $classes = SchoolClass::where('school_id', $school->id)
            ->where('status', 'active')
            ->with(['arms' => function ($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('name')
            ->get();

        $branches = SchoolBranch::where('school_id', $school->id)
            ->where('status', 'active')
            ->get();

        return view('school-website.apply', compact('school', 'classes', 'branches'));
    }

    /**
     * Submit online student admission application.
     */
    public function submitAdmission(Request $request, string $slug)
    {
        $school = $this->getSchool($slug);

        if ($school->admission_status === 'closed') {
            return redirect()->back()->with('error', 'Admissions for '.$school->name.' are currently closed. Please contact the school directly.');
        }

        $validated = $request->validate([
            'school_branch_id' => 'nullable|exists:school_branches,id',
            'preferred_branch' => 'nullable|string|max:150',
            'applied_class_id' => 'required|exists:classes,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date|before:today',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'guardian_email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'previous_school' => 'nullable|string|max:255',
            // Documents
            'birth_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:3072',
            'passport_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'school_report' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072',
        ]);

        // Resolve branch ID
        $branchId = $request->school_branch_id;
        if (! $branchId && $request->filled('preferred_branch')) {
            $branch = SchoolBranch::firstOrCreate([
                'school_id' => $school->id,
                'name' => trim($request->preferred_branch),
            ]);
            $branchId = $branch->id;
        }

        // Generate application number
        $year = date('Y');
        $count = AdmissionApplication::where('school_id', $school->id)->whereYear('created_at', $year)->count() + 1;
        $schoolPrefix = $school->school_code ? strtoupper($school->school_code) : 'APP';
        $applicationNo = $schoolPrefix.'-'.$year.'-'.str_pad($count, 4, '0', STR_PAD_LEFT);

        while (AdmissionApplication::where('application_no', $applicationNo)->exists()) {
            $count++;
            $applicationNo = $schoolPrefix.'-'.$year.'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
        }

        // Save application
        $application = AdmissionApplication::create([
            'school_id' => $school->id,
            'school_branch_id' => $branchId,
            'application_no' => $applicationNo,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'other_name' => $validated['other_name'] ?? null,
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'guardian_name' => $validated['guardian_name'],
            'guardian_phone' => $validated['guardian_phone'],
            'guardian_email' => $validated['guardian_email'],
            'address' => $validated['address'],
            'previous_school' => $validated['previous_school'] ?? null,
            'applied_class_id' => $validated['applied_class_id'],
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Document uploads
        $documents = [
            'birth_certificate' => 'Birth Certificate',
            'passport_photo' => 'Passport Photograph',
            'school_report' => 'Previous School Report Card',
            'medical_certificate' => 'Medical Fitness Certificate',
        ];

        foreach ($documents as $fieldName => $documentName) {
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $fileName = $applicationNo.'_'.str_replace(' ', '_', strtolower($documentName)).'.'.$file->getClientOriginalExtension();
                $filePath = $file->storeAs('admission-documents', $fileName, 'public');

                AdmissionDocument::create([
                    'application_id' => $application->id,
                    'document_name' => $documentName,
                    'file' => $filePath,
                    'uploaded_at' => now(),
                ]);
            }
        }

        // Send confirmation email
        try {
            Mail::to($application->guardian_email)->send(new AdmissionAppliedMail($application));
        } catch (\Exception $e) {
            logger()->warning('Failed to send admission confirmation email: '.$e->getMessage());
        }

        return redirect()->back()->with('success_application', [
            'application_no' => $application->application_no,
            'student_name' => $application->first_name.' '.$application->last_name,
            'guardian_email' => $application->guardian_email,
            'school_name' => $school->name,
        ]);
    }

    /**
     * Show the careers and job openings page for this school.
     */
    public function careers(string $slug)
    {
        $school = $this->getSchool($slug);

        $branches = SchoolBranch::where('school_id', $school->id)
            ->where('status', 'active')
            ->get();

        return view('school-website.careers', compact('school', 'branches'));
    }

    /**
     * Submit a job application for this school.
     */
    public function submitJobApplication(Request $request, string $slug)
    {
        $school = $this->getSchool($slug);

        $validated = $request->validate([
            'school_branch_id' => ['nullable', 'exists:school_branches,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'position_applied' => ['required', 'string', 'max:255'],
            'qualification' => ['required', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'years_of_experience' => ['required', 'integer', 'min:0', 'max:50'],
            'previous_employer' => ['nullable', 'string', 'max:255'],
            'expected_salary' => ['nullable', 'numeric', 'min:0'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'resume_cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB max
            'certificates' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        // Upload Resume / CV
        $resumePath = null;
        if ($request->hasFile('resume_cv')) {
            $resumePath = $request->file('resume_cv')->store('applicant-resumes', 'public');
        }

        // Upload Certificates
        $certPath = null;
        if ($request->hasFile('certificates')) {
            $certPath = $request->file('certificates')->store('applicant-certificates', 'public');
        }

        $jobApp = StaffJobApplication::create([
            'school_id' => $school->id,
            'school_branch_id' => $validated['school_branch_id'] ?? null,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'position_applied' => $validated['position_applied'],
            'qualification' => $validated['qualification'],
            'specialization' => $validated['specialization'] ?? null,
            'years_of_experience' => $validated['years_of_experience'],
            'previous_employer' => $validated['previous_employer'] ?? null,
            'expected_salary' => $validated['expected_salary'] ?? null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'resume_cv' => $resumePath,
            'certificates' => $certPath,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success_career', [
            'applicant_name' => $jobApp->full_name,
            'position' => $jobApp->position_applied,
            'school_name' => $school->name,
            'email' => $jobApp->email,
        ]);
    }
}
