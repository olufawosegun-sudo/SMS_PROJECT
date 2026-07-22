<?php

namespace App\Http\Controllers;

use App\Models\CbtExam;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Staff;
use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CbtExamController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $userRole = Auth::user()->role->name;
        
        $classes = SchoolClass::where('school_id', $school->id)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $school->id)->orderBy('name')->get();
        $teachers = Staff::where('school_id', $school->id)->where('staff_type', 'Teacher')->where('status', 'active')->with('user')->get();
        $sessions = AcademicSession::where('school_id', $school->id)->orderBy('name', 'desc')->get();
        $terms = AcademicTerm::where('school_id', $school->id)->orderBy('name')->get();

        // Get assessments list to serve as the Question Bank
        $assessments = \App\Models\ContinuousAssessment::where('school_id', $school->id)
            ->with(['schoolClass', 'subject'])
            ->orderBy('title')
            ->get();

        // Get exams based on role and status filter
        $statusFilter = request()->get('status');
        
        $examsQuery = CbtExam::where('school_id', $school->id)
            ->with(['subject', 'schoolClass', 'session', 'term', 'createdBy', 'assessment']);

        // Filter by status if provided
        if ($statusFilter) {
            $examsQuery->where('status', $statusFilter);
        }

        // Role-based filtering
        if ($userRole === 'Teacher') {
            // Teachers only see their own exams
            $examsQuery->where('created_by', Auth::id());
        }
        // Principal and Owner see all exams

        $exams = $examsQuery->orderBy('created_at', 'desc')->get();

        // Count exams by status for dashboard
        $statusCounts = [
            'draft' => CbtExam::where('school_id', $school->id)->where('status', 'draft')->count(),
            'pending_approval' => CbtExam::where('school_id', $school->id)->where('status', 'pending_approval')->count(),
            'approved' => CbtExam::where('school_id', $school->id)->where('status', 'approved')->count(),
            'needs_revision' => CbtExam::where('school_id', $school->id)->where('status', 'needs_revision')->count(),
            'rejected' => CbtExam::where('school_id', $school->id)->where('status', 'rejected')->count(),
        ];

        return view('cbt-exams.index', compact('classes', 'subjects', 'teachers', 'exams', 'school', 'userRole', 'statusCounts', 'statusFilter', 'sessions', 'terms', 'assessments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'assessment_id' => 'required|exists:continuous_assessments,id',
            'session_id' => 'required|exists:academic_sessions,id',
            'term_id' => 'required|exists:academic_terms,id',
            'duration' => 'required|integer|min:5',
            'total_marks' => 'required|integer|min:1',
        ]);

        $school = Auth::user()->school;

        CbtExam::create([
            'school_id' => $school->id,
            'title' => $request->title,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'assessment_id' => $request->assessment_id,
            'session_id' => $request->session_id,
            'term_id' => $request->term_id,
            'duration' => $request->duration,
            'total_marks' => $request->total_marks,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'CBT Exam created successfully! The linked Question Bank has been assigned.');
    }

    public function show($id)
    {
        $school = Auth::user()->school;
        $userRole = Auth::user()->role->name;
        
        $exam = CbtExam::where('school_id', $school->id)
            ->with(['subject', 'schoolClass', 'session', 'term', 'createdBy', 'approvedBy', 'rejectedBy', 'assessment.questions.options'])
            ->findOrFail($id);

        return view('cbt-exams.show', compact('exam', 'school', 'userRole'));
    }

    public function edit($id)
    {
        $school = Auth::user()->school;
        $userRole = Auth::user()->role->name;
        
        $exam = CbtExam::where('school_id', $school->id)->findOrFail($id);

        // Check if exam can be edited
        if (!$exam->canEdit()) {
            return redirect()->route('cbt-exams.show', $id)
                ->with('error', 'This exam cannot be edited. Status: ' . $exam->getStatusLabel());
        }

        // Only creator or Principal can edit
        if ($userRole !== 'Principal' && $exam->created_by !== Auth::id()) {
            return redirect()->route('cbt-exams.index')
                ->with('error', 'You do not have permission to edit this exam.');
        }

        $classes = SchoolClass::where('school_id', $school->id)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $school->id)->orderBy('name')->get();
        $sessions = AcademicSession::where('school_id', $school->id)->orderBy('name', 'desc')->get();
        $terms = AcademicTerm::where('school_id', $school->id)->orderBy('name')->get();
        
        $assessments = \App\Models\ContinuousAssessment::where('school_id', $school->id)
            ->with(['schoolClass', 'subject'])
            ->orderBy('title')
            ->get();

        return view('cbt-exams.edit', compact('exam', 'classes', 'subjects', 'sessions', 'terms', 'school', 'userRole', 'assessments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'assessment_id' => 'required|exists:continuous_assessments,id',
            'session_id' => 'required|exists:academic_sessions,id',
            'term_id' => 'required|exists:academic_terms,id',
            'duration' => 'required|integer|min:5',
            'total_marks' => 'required|integer|min:1',
        ]);

        $school = Auth::user()->school;
        $exam = CbtExam::where('school_id', $school->id)->findOrFail($id);

        // Check if exam can be edited
        if (!$exam->canEdit()) {
            return redirect()->back()->with('error', 'This exam cannot be edited.');
        }

        $exam->update([
            'title' => $request->title,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'assessment_id' => $request->assessment_id,
            'session_id' => $request->session_id,
            'term_id' => $request->term_id,
            'duration' => $request->duration,
            'total_marks' => $request->total_marks,
        ]);

        return redirect()->route('cbt-exams.show', $id)->with('success', 'CBT Exam updated successfully!');
    }

    public function destroy($id)
    {
        $school = Auth::user()->school;
        $userRole = Auth::user()->role->name;
        $exam = CbtExam::where('school_id', $school->id)->findOrFail($id);

        // Only allow deletion of draft exams by creator or Principal
        if ($exam->status !== 'draft') {
            return redirect()->back()->with('error', 'Only draft exams can be deleted.');
        }

        if ($userRole !== 'Principal' && $exam->created_by !== Auth::id()) {
            return redirect()->back()->with('error', 'You do not have permission to delete this exam.');
        }

        $exam->delete();
        return redirect()->route('cbt-exams.index')->with('success', 'CBT Exam deleted successfully!');
    }

    // Submit exam for approval (Teacher)
    public function submitForApproval($id)
    {
        $school = Auth::user()->school;
        $exam = CbtExam::where('school_id', $school->id)->findOrFail($id);

        // Only draft or needs_revision exams can be submitted
        if (!in_array($exam->status, ['draft', 'needs_revision'])) {
            return redirect()->back()->with('error', 'This exam cannot be submitted for approval.');
        }

        // Only creator can submit
        if ($exam->created_by !== Auth::id()) {
            return redirect()->back()->with('error', 'You do not have permission to submit this exam.');
        }

        $exam->update([
            'status' => 'pending_approval',
            'submitted_at' => now(),
            'submitted_by' => Auth::id(),
        ]);

        // TODO: Send notification to Principal

        return redirect()->route('cbt-exams.index')->with('success', '✅ Exam submitted for approval! The Principal will review it soon.');
    }

    // Approve exam (Principal)
    public function approve($id)
    {
        $school = Auth::user()->school;
        $userRole = Auth::user()->role->name;

        if (!in_array($userRole, ['Principal', 'Owner'])) {
            return redirect()->back()->with('error', 'Only Principal can approve exams.');
        }

        $exam = CbtExam::where('school_id', $school->id)->findOrFail($id);

        if ($exam->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Only pending exams can be approved.');
        }

        $exam->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // TODO: Send notification to teacher

        return redirect()->route('cbt-exams.index')->with('success', '✅ Exam approved successfully! The teacher can now schedule it.');
    }

    // Return exam for revision (Principal)
    public function returnForRevision(Request $request, $id)
    {
        $request->validate([
            'principal_comment' => 'required|string|max:1000',
        ]);

        $school = Auth::user()->school;
        $userRole = Auth::user()->role->name;

        if (!in_array($userRole, ['Principal', 'Owner'])) {
            return redirect()->back()->with('error', 'Only Principal can return exams for revision.');
        }

        $exam = CbtExam::where('school_id', $school->id)->findOrFail($id);

        if ($exam->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Only pending exams can be returned for revision.');
        }

        $exam->update([
            'status' => 'needs_revision',
            'principal_comment' => $request->principal_comment,
            'returned_at' => now(),
        ]);

        // TODO: Send notification to teacher with comments

        return redirect()->route('cbt-exams.index')->with('success', '✅ Exam returned for revision. The teacher has been notified.');
    }

    // Reject exam (Principal)
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $school = Auth::user()->school;
        $userRole = Auth::user()->role->name;

        if (!in_array($userRole, ['Principal', 'Owner'])) {
            return redirect()->back()->with('error', 'Only Principal can reject exams.');
        }

        $exam = CbtExam::where('school_id', $school->id)->findOrFail($id);

        if ($exam->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Only pending exams can be rejected.');
        }

        $exam->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
        ]);

        // TODO: Send notification to teacher with rejection reason

        return redirect()->route('cbt-exams.index')->with('success', '✅ Exam rejected. The teacher has been notified.');
    }
}
