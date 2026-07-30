<?php

namespace App\Http\Controllers;

use App\Services\StudentService;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\ClassArm;
use App\Models\SchoolBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected $studentService;

    /**
     * Create a new controller instance.
     */
    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Display a listing of students.
     */
    public function index()
    {
        $this->authorize('viewAny', Student::class);

        $school = Auth::user()->school;
        
        $students = $this->studentService->getSchoolStudents($school->id, 20);
        $stats = $this->studentService->getSchoolStats($school->id);

        return view('students.index', compact('students', 'stats', 'school'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $this->authorize('create', Student::class);

        $school = Auth::user()->school;
        $classes = SchoolClass::where('school_id', $school->id)->get();
        $arms = ClassArm::whereHas('schoolClass', function($query) use ($school) {
            $query->where('school_id', $school->id);
        })->get();
        $branches = SchoolBranch::where('school_id', $school->id)->where('status', 'active')->get();

        return view('students.create', compact('classes', 'arms', 'branches', 'school'));
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $this->authorize('create', Student::class);

        $school = Auth::user()->school;

        try {
            $student = $this->studentService->createStudent(
                $request->validated(),
                $school->id,
                $school->branch_id ?? null
            );

            return redirect()->route('students.index')
                ->with('success', 'Student enrolled successfully! Welcome email has been queued for delivery.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to enroll student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $this->authorize('view', $student);

        $school = Auth::user()->school;

        try {
            $student = $this->studentService->findStudent($student->id, $school->id);
            return view('students.show', compact('student'));

        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $this->authorize('update', $student);

        $school = Auth::user()->school;

        try {
            $student = $this->studentService->findStudent($student->id, $school->id);

            $classes = SchoolClass::where('school_id', $school->id)->get();
            $arms = ClassArm::whereHas('schoolClass', function($query) use ($school) {
                $query->where('school_id', $school->id);
            })->get();

            return view('students.edit', compact('student', 'classes', 'arms', 'school'));

        } catch (\Exception $e) {
            abort(403, $e->getMessage());
        }
    }

    /**
     * Update the specified student in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $this->authorize('update', $student);

        $school = Auth::user()->school;

        try {
            $updatedStudent = $this->studentService->updateStudent(
                $student->id,
                $request->validated(),
                $school->id
            );

            return redirect()->route('students.index')
                ->with('success', 'Student updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);

        $school = Auth::user()->school;

        try {
            $this->studentService->deleteStudent($student->id, $school->id);

            return redirect()->route('students.index')
                ->with('success', 'Student deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete student: ' . $e->getMessage());
        }
    }
}
