<?php

namespace App\Http\Controllers;

use App\Models\ContinuousAssessment;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::user()->school;
        $classes = SchoolClass::where('school_id', $school->id)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $school->id)->orderBy('name')->get();
        $teachers = Staff::where('school_id', $school->id)->where('staff_type', 'Teacher')->where('status', 'active')->with('user')->get();

        $selectedClassId = $request->get('class_id');

        $assessmentsQuery = ContinuousAssessment::where('school_id', $school->id)
            ->with(['schoolClass', 'subject', 'teacher.user']);

        if ($selectedClassId) {
            $assessmentsQuery->where('class_id', $selectedClassId);
        }

        $assessments = $assessmentsQuery->orderBy('created_at', 'desc')->get();

        return view('assessments.index', compact('classes', 'subjects', 'teachers', 'assessments', 'selectedClassId', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:staffs,id',
            'title' => 'required|string|max:100',
            'max_score' => 'required|numeric|min:1',
            'weight' => 'required|numeric|min:1|max:100',
        ]);

        $school = Auth::user()->school;

        ContinuousAssessment::create([
            'school_id' => $school->id,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'title' => $request->title,
            'max_score' => $request->max_score,
            'weight' => $request->weight,
            'status' => 'published',
        ]);

        return redirect()->back()->with('success', 'Assessment added successfully!');
    }

    public function destroy($id)
    {
        ContinuousAssessment::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Assessment deleted successfully!');
    }
}
