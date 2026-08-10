<?php

namespace App\Http\Controllers;

use App\Mail\AdmissionOfferMail;
use App\Mail\StudentWelcomeMail;
use App\Models\AcademicSession;
use App\Models\AdmissionApplication;
use App\Models\AdmissionOffer;
use App\Models\ClassArm;
use App\Models\Guardian;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdmissionController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $applications = AdmissionApplication::where('school_id', $school->id)
            ->with(['appliedClass', 'documents', 'offer'])
            ->orderBy('submitted_at', 'desc')
            ->get();

        // For the enroll modal
        $classes = SchoolClass::where('school_id', $school->id)->where('status', 'active')->get();
        $arms = ClassArm::whereIn('class_id', $classes->pluck('id'))->where('status', 'active')->get();

        return view('admissions.index', compact('applications', 'school', 'classes', 'arms'));
    }

    public function update(Request $request, $id)
    {
        $application = AdmissionApplication::findOrFail($id);
        $request->validate([
            'status' => 'required|in:submitted,under_review,offered,rejected',
        ]);

        $application->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Application status updated successfully!');
    }

    /**
     * Generate and send offer letter
     */
    public function sendOffer($id)
    {
        $school = Auth::user()->school;
        $application = AdmissionApplication::where('school_id', $school->id)
            ->with(['appliedClass', 'documents'])
            ->findOrFail($id);

        // Check if offer already exists
        if ($application->offer) {
            return redirect()->back()->with('error', 'Offer letter has already been sent for this application.');
        }

        // Create admission offer record
        $offer = AdmissionOffer::create([
            'application_id' => $application->id,
            'offered_class_id' => $application->applied_class_id,
            'offered_by' => Auth::id(),
            'status' => 'pending',
            'offer_date' => now(),
        ]);

        // Update application status
        $application->update(['status' => 'offered']);

        // Generate PDF offer letter
        $currentSession = AcademicSession::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();

        $pdf = Pdf::loadView('pdfs.offer-letter', [
            'application' => $application,
            'offer' => $offer,
            'school' => $school,
            'offeredClass' => $application->appliedClass,
            'currentSession' => $currentSession,
        ]);

        // Save PDF to storage
        $pdfFileName = 'offer_letter_'.$application->application_no.'.pdf';
        $pdfPath = 'offer-letters/'.$pdfFileName;
        \Storage::disk('public')->put($pdfPath, $pdf->output());

        // Send email with offer letter (will implement in next step)
        try {
            Mail::to($application->guardian_email)->send(
                new AdmissionOfferMail($application, $offer, $pdfPath)
            );
        } catch (\Exception $e) {
            logger()->warning('Failed to send offer email: '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'Offer letter generated and sent successfully!');
    }

    /**
     * Get class arms for a given class (AJAX)
     */
    public function getArmsForEnroll($classId)
    {
        try {
            $arms = ClassArm::where('class_id', $classId)
                ->where('status', 'active')
                ->get(['id', 'name']);

            return response()->json($arms);
        } catch (\Exception $e) {
            \Log::error('getArmsForEnroll error: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Enroll an accepted applicant as an official student
     */
    public function enrollStudent(Request $request, $id)
    {
        $school = Auth::user()->school;
        $application = AdmissionApplication::where('school_id', $school->id)
            ->with(['offer', 'appliedClass', 'documents'])
            ->findOrFail($id);

        // Must have an accepted offer
        if (! $application->offer || $application->offer->status !== 'accepted') {
            return redirect()->back()->with('error', 'This application does not have an accepted offer.');
        }

        // Prevent double enrollment
        if ($application->status === 'enrolled') {
            return redirect()->back()->with('error', 'This student has already been enrolled.');
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'arm_id' => 'nullable|exists:class_arms,id',
        ]);

        // Get or create student role
        $studentRole = Role::where('school_id', $school->id)->where('name', 'Student')->first();

        // Generate a unique email for the student account if guardian email is already used
        $email = $application->guardian_email;
        $studentEmail = null;
        // Give student their own email slot only if different from guardian
        if (! User::where('email', $email)->exists()) {
            $studentEmail = $email;
        }

        // Create User account
        $defaultPassword = 'School@'.date('Y');
        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $school->id,
            'role_id' => $studentRole ? $studentRole->id : null,
            'first_name' => $application->first_name,
            'last_name' => $application->last_name,
            'email' => $studentEmail,
            'gender' => strtolower($application->gender),
            'date_of_birth' => $application->dob,
            'password' => Hash::make($defaultPassword),
            'status' => 'active',
        ]);

        // Generate admission number
        $year = date('Y');
        $count = Student::whereYear('created_at', $year)->count() + 1;
        $admissionNo = $school->code ?? 'STU';
        $admissionNo .= $year.str_pad($count, 4, '0', STR_PAD_LEFT);
        while (Student::where('admission_no', $admissionNo)->exists()) {
            $count++;
            $admissionNo = ($school->code ?? 'STU').$year.str_pad($count, 4, '0', STR_PAD_LEFT);
        }

        // Create Student profile
        $student = Student::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $school->id,
            'user_id' => $user->id,
            'admission_no' => $admissionNo,
            'class_id' => $request->class_id,
            'arm_id' => $request->arm_id ?? null,
            'admission_date' => now(),
            'status' => 'active',
        ]);

        // Copy admission documents to student documents
        if ($application->documents && $application->documents->count() > 0) {
            foreach ($application->documents as $admissionDoc) {
                // Map admission document names to student document types
                $documentTypeMap = [
                    'Birth Certificate' => StudentDocument::TYPE_BIRTH_CERTIFICATE,
                    'Passport Photograph' => StudentDocument::TYPE_PASSPORT_PHOTO,
                    'Previous School Report Card' => StudentDocument::TYPE_PREVIOUS_SCHOOL_RECORD,
                    'Medical Fitness Certificate' => StudentDocument::TYPE_MEDICAL_RECORD,
                ];

                $documentType = $documentTypeMap[$admissionDoc->document_name] ?? StudentDocument::TYPE_OTHER;

                // Get file info
                $filePath = $admissionDoc->file;
                $fileSize = null;
                $mimeType = null;

                if (\Storage::disk('public')->exists($filePath)) {
                    $fileSize = round(\Storage::disk('public')->size($filePath) / 1024, 2); // KB
                    $mimeType = \Storage::disk('public')->mimeType($filePath);
                }

                // Create student document record
                StudentDocument::create([
                    'school_id' => $school->id,
                    'student_id' => $student->id,
                    'document_type' => $documentType,
                    'document_name' => $admissionDoc->document_name,
                    'file_path' => $filePath,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType,
                    'uploaded_by' => Auth::id(),
                    'uploaded_at' => $admissionDoc->uploaded_at ?? now(),
                    'status' => 'active',
                    'notes' => 'Uploaded during admission application',
                ]);
            }
        }

        // Mark application as enrolled
        $application->update(['status' => 'enrolled']);

        // Send welcome email to guardian with student login details
        if ($studentEmail) {
            try {
                Mail::to($studentEmail)->send(new StudentWelcomeMail($student->load(['user', 'schoolClass', 'arm']), $defaultPassword));
            } catch (\Exception $e) {
                \Log::warning('Failed to send student welcome email: '.$e->getMessage());
            }
        }

        return redirect()->route('dashboard')->with('success',
            $application->first_name.' '.$application->last_name.
            ' has been enrolled successfully! Admission No: '.$admissionNo
        );
    }

    /**
     * Download offer letter PDF
     */
    public function downloadOffer($id)
    {
        $school = Auth::user()->school;
        $application = AdmissionApplication::where('school_id', $school->id)
            ->with(['appliedClass', 'offer'])
            ->findOrFail($id);

        if (! $application->offer) {
            return redirect()->back()->with('error', 'No offer letter found for this application.');
        }

        $currentSession = AcademicSession::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();

        $pdf = Pdf::loadView('pdfs.offer-letter', [
            'application' => $application,
            'offer' => $application->offer,
            'school' => $school,
            'offeredClass' => $application->appliedClass,
            'currentSession' => $currentSession,
        ]);

        return $pdf->download('offer_letter_'.$application->application_no.'.pdf');
    }
}
