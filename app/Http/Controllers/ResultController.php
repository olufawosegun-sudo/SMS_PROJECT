<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::user()->school;
        $classes = SchoolClass::where('school_id', $school->id)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $school->id)->orderBy('name')->get();
        $selectedClassId = $request->get('class_id');

        $resultsQuery = Result::where('school_id', $school->id)
            ->with(['student.user', 'schoolClass', 'subject', 'approvals']);

        if ($selectedClassId) {
            $resultsQuery->where('class_id', $selectedClassId);
        }

        $results = $resultsQuery->orderBy('created_at', 'desc')->get();

        // Get all students for the dropdown (will be filtered by JavaScript)
        $students = Student::where('school_id', $school->id)
            ->where('status', 'active')
            ->with(['user', 'schoolClass'])
            ->get()
            ->sortBy(function($student) {
                return $student->user->first_name ?? '';
            })
            ->values();

        return view('results.index', compact('classes', 'subjects', 'results', 'selectedClassId', 'school', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'ca_score' => 'required|numeric|min:0',
            'exam_score' => 'required|numeric|min:0',
        ]);

        $school = Auth::user()->school;
        $total = $request->ca_score + $request->exam_score;

        // Determine grade
        $grade = match(true) {
            $total >= 70 => 'A',
            $total >= 60 => 'B',
            $total >= 50 => 'C',
            $total >= 40 => 'D',
            default => 'F',
        };

        // Resolve session and term (from request or current)
        $sessionId = $request->session_id ?? (
            \App\Models\AcademicSession::where('school_id', $school->id)->where('is_current', true)->first()
            ?? \App\Models\AcademicSession::where('school_id', $school->id)->first()
        )->id ?? 1;
        
        $termId = $request->term_id ?? (
            \App\Models\AcademicTerm::where('school_id', $school->id)->where('is_current', true)->first()
            ?? \App\Models\AcademicTerm::where('school_id', $school->id)->first()
        )->id ?? 1;

        $result = Result::updateOrCreate(
            [
                'school_id' => $school->id,
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'session_id' => $sessionId,
                'term_id' => $termId,
            ],
            [
                'ca_score' => $request->ca_score,
                'exam_score' => $request->exam_score,
                'total_score' => $total,
                'grade' => $grade,
                'recorded_by' => Auth::id(),
                'recorded_at' => now(),
            ]
        );

        // Check if request expects JSON (AJAX)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Result recorded successfully!',
                'data' => $result
            ]);
        }

        return redirect()->back()->with('success', 'Result recorded successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ca_score' => 'required|numeric|min:0|max:40',
            'exam_score' => 'required|numeric|min:0|max:60',
        ]);

        $result = Result::findOrFail($id);
        
        // Verify ownership
        if ($result->school_id !== Auth::user()->school->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Lock if approved and user is not Principal/Owner
        $isApproved = $result->approvals()->where('status', 'approved')->exists();
        if ($isApproved && !in_array(Auth::user()->role->name, ['Principal', 'Owner'])) {
            return response()->json(['success' => false, 'message' => 'This result is already approved and locked.'], 403);
        }

        $total = $request->ca_score + $request->exam_score;

        // Determine grade
        $grade = match(true) {
            $total >= 70 => 'A',
            $total >= 60 => 'B',
            $total >= 50 => 'C',
            $total >= 40 => 'D',
            default => 'F',
        };

        $result->update([
            'ca_score' => $request->ca_score,
            'exam_score' => $request->exam_score,
            'total_score' => $total,
            'grade' => $grade,
            'recorded_by' => Auth::id(),
            'recorded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Score updated successfully!',
            'data' => [
                'ca_score' => $result->ca_score,
                'exam_score' => $result->exam_score,
                'total_score' => $result->total_score,
                'grade' => $grade,
            ]
        ]);
    }

    public function destroy($id)
    {
        $result = Result::findOrFail($id);

        // Lock if approved and user is not Principal/Owner
        $isApproved = $result->approvals()->where('status', 'approved')->exists();
        if ($isApproved && !in_array(Auth::user()->role->name, ['Principal', 'Owner'])) {
            return redirect()->back()->with('error', 'This result is already approved and locked.');
        }

        $result->delete();
        return redirect()->back()->with('success', 'Result deleted successfully!');
    }

    public function approve($id)
    {
        $userRole = Auth::user()->role->name;
        if (!in_array($userRole, ['Principal', 'Owner'])) {
            return redirect()->back()->with('error', 'Only the Principal or Owner can approve results.');
        }

        $result = Result::findOrFail($id);

        \App\Models\ResultApproval::updateOrCreate(
            ['result_id' => $result->id],
            [
                'approved_by' => Auth::id(),
                'status' => 'approved',
                'approved_at' => now(),
                'remarks' => 'Approved from grades sheet.'
            ]
        );

        return redirect()->back()->with('success', 'Result approved successfully!');
    }

    public function batchApprove(Request $request)
    {
        $userRole = Auth::user()->role->name;
        if (!in_array($userRole, ['Principal', 'Owner'])) {
            return redirect()->back()->with('error', 'Only the Principal or Owner can approve results.');
        }

        $request->validate([
            'result_ids' => 'required|array',
            'result_ids.*' => 'exists:results,id'
        ]);

        foreach ($request->result_ids as $resultId) {
            \App\Models\ResultApproval::updateOrCreate(
                ['result_id' => $resultId],
                [
                    'approved_by' => Auth::id(),
                    'status' => 'approved',
                    'approved_at' => now(),
                    'remarks' => 'Batch approved from grades sheet.'
                ]
            );
        }

        return redirect()->back()->with('success', 'Selected results approved successfully!');
    }
}
