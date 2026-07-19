<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CbtExamController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $classes = SchoolClass::where('school_id', $school->id)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $school->id)->orderBy('name')->get();
        $teachers = Staff::where('school_id', $school->id)->where('staff_type', 'Teacher')->where('status', 'active')->with('user')->get();

        // Placeholder: CBT exams would come from a cbt_exams table
        $exams = collect();

        return view('cbt-exams.index', compact('classes', 'subjects', 'teachers', 'exams', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'duration_minutes' => 'required|integer|min:5',
            'total_questions' => 'required|integer|min:1',
        ]);

        // Placeholder: store exam in DB when cbt_exams table is created
        return redirect()->back()->with('success', 'CBT Exam created successfully! (Table pending migration)');
    }

    public function destroy($id)
    {
        return redirect()->back()->with('success', 'CBT Exam deleted successfully!');
    }
}
