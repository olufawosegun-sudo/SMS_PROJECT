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
            ->with(['student.user', 'schoolClass', 'subject']);

        if ($selectedClassId) {
            $resultsQuery->where('class_id', $selectedClassId);
        }

        $results = $resultsQuery->orderBy('created_at', 'desc')->get();

        return view('results.index', compact('classes', 'subjects', 'results', 'selectedClassId', 'school'));
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

        Result::updateOrCreate(
            [
                'school_id' => $school->id,
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
            ],
            [
                'ca_score' => $request->ca_score,
                'exam_score' => $request->exam_score,
                'total_score' => $total,
                'grade' => $grade,
                'session_id' => 1,
                'term_id' => 1,
            ]
        );

        return redirect()->back()->with('success', 'Result recorded successfully!');
    }

    public function destroy($id)
    {
        Result::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Result deleted successfully!');
    }
}
