<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\StudentPromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $students = Student::where('school_id', $school->id)->with(['user', 'schoolClass'])->get();
        $classes = SchoolClass::where('school_id', $school->id)->get();
        $promotions = StudentPromotion::with(['student.user', 'fromClass', 'toClass'])
            ->whereHas('student', function($q) use ($school) {
                $q->where('school_id', $school->id);
            })
            ->orderBy('promotion_date', 'desc')
            ->get();

        return view('promotions.index', compact('students', 'classes', 'promotions', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'to_class_id' => 'required|exists:classes,id',
            'remarks' => 'nullable|string|max:255',
        ]);

        $student = Student::findOrFail($request->student_id);
        $fromClassId = $student->class_id;

        // Create promotion history
        StudentPromotion::create([
            'student_id' => $student->id,
            'from_class_id' => $fromClassId,
            'to_class_id' => $request->to_class_id,
            'session_id' => 1, // Default session
            'promoted_by' => Auth::id(),
            'promotion_date' => now(),
            'remarks' => $request->remarks,
        ]);

        // Update student class
        $student->update([
            'class_id' => $request->to_class_id
        ]);

        return redirect()->back()->with('success', 'Student promoted successfully!');
    }
}
