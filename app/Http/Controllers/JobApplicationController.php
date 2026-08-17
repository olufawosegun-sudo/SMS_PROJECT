<?php

namespace App\Http\Controllers;

use App\Models\StaffJobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of job applications for the authenticated school.
     */
    public function index(Request $request)
    {
        $school = Auth::user()->school;

        $query = StaffJobApplication::where('school_id', $school->id)
            ->with(['schoolBranch', 'approvedByUser'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or email or position
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('position_applied', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => StaffJobApplication::where('school_id', $school->id)->count(),
            'pending' => StaffJobApplication::where('school_id', $school->id)->where('status', 'pending')->count(),
            'shortlisted' => StaffJobApplication::where('school_id', $school->id)->where('status', 'shortlisted')->count(),
            'approved' => StaffJobApplication::where('school_id', $school->id)->where('status', 'approved')->count(),
            'rejected' => StaffJobApplication::where('school_id', $school->id)->where('status', 'rejected')->count(),
        ];

        return view('job-applications.index', compact('applications', 'stats', 'school'));
    }

    /**
     * Display the specified job application.
     */
    public function show($id)
    {
        $school = Auth::user()->school;

        $application = StaffJobApplication::where('school_id', $school->id)
            ->with(['schoolBranch', 'approvedByUser'])
            ->findOrFail($id);

        return view('job-applications.show', compact('application', 'school'));
    }

    /**
     * Update the status of a job application.
     */
    public function updateStatus(Request $request, $id)
    {
        $school = Auth::user()->school;

        $application = StaffJobApplication::where('school_id', $school->id)->findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,shortlisted,interviewed,approved,rejected'],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $updateData = [
            'status' => $validated['status'],
            'rejection_reason' => $validated['rejection_reason'] ?? $application->rejection_reason,
        ];

        if ($validated['status'] === 'approved') {
            $updateData['approved_at'] = now();
            $updateData['approved_by'] = Auth::id();
        }

        $application->update($updateData);

        return redirect()->back()->with('success', "Application status updated to {$validated['status']} successfully!");
    }

    /**
     * Download the applicant's resume.
     */
    public function downloadResume($id)
    {
        $school = Auth::user()->school;

        $application = StaffJobApplication::where('school_id', $school->id)->findOrFail($id);

        if (! $application->resume_cv || ! Storage::disk('public')->exists($application->resume_cv)) {
            return redirect()->back()->with('error', 'Resume file not found.');
        }

        return Storage::disk('public')->download($application->resume_cv, Str::slug($application->full_name).'_Resume.'.pathinfo($application->resume_cv, PATHINFO_EXTENSION));
    }

    /**
     * Download certificates if uploaded.
     */
    public function downloadCertificates($id)
    {
        $school = Auth::user()->school;

        $application = StaffJobApplication::where('school_id', $school->id)->findOrFail($id);

        if (! $application->certificates || ! Storage::disk('public')->exists($application->certificates)) {
            return redirect()->back()->with('error', 'Certificates file not found.');
        }

        return Storage::disk('public')->download($application->certificates, Str::slug($application->full_name).'_Certificates.'.pathinfo($application->certificates, PATHINFO_EXTENSION));
    }

    /**
     * Delete an application.
     */
    public function destroy($id)
    {
        $school = Auth::user()->school;

        $application = StaffJobApplication::where('school_id', $school->id)->findOrFail($id);

        if ($application->resume_cv && Storage::disk('public')->exists($application->resume_cv)) {
            Storage::disk('public')->delete($application->resume_cv);
        }
        if ($application->certificates && Storage::disk('public')->exists($application->certificates)) {
            Storage::disk('public')->delete($application->certificates);
        }

        $application->delete();

        return redirect()->route('job-applications.index')->with('success', 'Job application deleted successfully!');
    }
}
