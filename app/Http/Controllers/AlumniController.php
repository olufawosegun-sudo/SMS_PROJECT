<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumniController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $students = Student::where('school_id', $school->id)->with('user')->get();
        $alumni = Alumni::with('student.user')
            ->whereHas('student', function($q) use ($school) {
                $q->where('school_id', $school->id);
            })
            ->orderBy('graduation_year', 'desc')
            ->get();

        return view('alumni.index', compact('students', 'alumni', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'graduation_year' => 'required|integer|min:1900|max:2100',
            'current_occupation' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Alumni::create([
            'student_id' => $request->student_id,
            'graduation_year' => $request->graduation_year,
            'current_occupation' => $request->current_occupation,
            'organization' => $request->organization,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        $student = Student::findOrFail($request->student_id);
        $student->update(['status' => 'alumni']);

        return redirect()->back()->with('success', 'Student marked as Alumni successfully!');
    }
}
