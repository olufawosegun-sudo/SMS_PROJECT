<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\ClassArm;
use App\Mail\StudentWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index()
    {
        $school = Auth::user()->school;
        $students = Student::where('school_id', $school->id)
            ->with(['user', 'schoolClass', 'arm'])
            ->latest()
            ->paginate(20);

        $stats = [
            'active' => Student::where('school_id', $school->id)->where('status', 'active')->count(),
            'inactive' => Student::where('school_id', $school->id)->where('status', 'inactive')->count(),
            'male' => Student::where('school_id', $school->id)
                ->whereHas('user', function($q) {
                    $q->where('gender', 'male');
                })->count(),
        ];

        return view('students.index', compact('students', 'stats', 'school'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $school = Auth::user()->school;
        $classes = SchoolClass::where('school_id', $school->id)->get();
        $arms = ClassArm::whereHas('schoolClass', function($query) use ($school) {
            $query->where('school_id', $school->id);
        })->get();

        return view('students.create', compact('classes', 'arms', 'school'));
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $school = Auth::user()->school;
        
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'class_id' => [
                'required',
                Rule::exists('classes', 'id')->where('school_id', $school->id)
            ],
            'arm_id' => [
                'nullable',
                Rule::exists('class_arms', 'id')->whereIn('class_id', function($query) use ($school) {
                    $query->select('id')->from('classes')->where('school_id', $school->id);
                })
            ],
            'admission_no' => ['nullable', 'string', 'unique:students,admission_no'],
            'admission_date' => ['nullable', 'date'],
        ]);
        $studentRole = Role::where('school_id', $school->id)
            ->where('name', 'Student')
            ->first();

        // Handle profile photo upload
        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        // Create User account
        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $school->id,
            'role_id' => $studentRole->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'profile_photo' => $photoPath,
            'password' => Hash::make('password123'), // Default password
            'status' => 'active',
        ]);

        // Generate admission number if not provided
        $admissionNo = $validated['admission_no'] ?? 'STU' . date('Y') . str_pad($user->id, 5, '0', STR_PAD_LEFT);

        // Create Student profile
        $student = Student::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $school->id,
            'user_id' => $user->id,
            'admission_no' => $admissionNo,
            'class_id' => $validated['class_id'],
            'arm_id' => $validated['arm_id'] ?? null,
            'admission_date' => $validated['admission_date'] ?? now(),
            'status' => 'active',
        ]);

        // Send welcome email with default password (only if email is provided)
        if (!empty($validated['email'])) {
            try {
                Mail::to($user->email)->send(new StudentWelcomeMail($student, 'password123'));
            } catch (\Exception $e) {
                // Log error but don't fail the creation
                \Log::error('Failed to send student welcome email: ' . $e->getMessage());
            }
        }

        return redirect()->route('students.index')
            ->with('success', 'Student enrolled successfully! ' . (!empty($validated['email']) ? 'Welcome email sent with default password: password123' : 'Default password: password123'));
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        // Verify student belongs to current user's school
        $school = Auth::user()->school;
        if ($student->school_id !== $school->id) {
            abort(403, 'Unauthorized access to this student.');
        }

        $student->load(['user', 'schoolClass', 'arm', 'guardians']);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        // Verify student belongs to current user's school
        $school = Auth::user()->school;
        if ($student->school_id !== $school->id) {
            abort(403, 'Unauthorized access to this student.');
        }

        $classes = SchoolClass::where('school_id', $school->id)->get();
        $arms = ClassArm::whereHas('schoolClass', function($query) use ($school) {
            $query->where('school_id', $school->id);
        })->get();
        $student->load('user');

        return view('students.edit', compact('student', 'classes', 'arms', 'school'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        // SECURITY: Verify student belongs to current user's school
        $school = Auth::user()->school;
        if ($student->school_id !== $school->id) {
            abort(403, 'Unauthorized access to this student.');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email,' . $student->user_id],
            'phone' => ['nullable', 'string', 'max:50'],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'class_id' => [
                'required',
                Rule::exists('classes', 'id')->where('school_id', $school->id)
            ],
            'arm_id' => [
                'nullable',
                Rule::exists('class_arms', 'id')->whereIn('class_id', function($query) use ($school) {
                    $query->select('id')->from('classes')->where('school_id', $school->id);
                })
            ],
            'status' => ['required', 'in:active,inactive,graduated'],
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $student->user->update(['profile_photo' => $photoPath]);
        }

        // Update User account
        $student->user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'status' => $validated['status'],
        ]);

        // Update Student profile
        $student->update([
            'class_id' => $validated['class_id'],
            'arm_id' => $validated['arm_id'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        // Verify student belongs to current user's school
        $school = Auth::user()->school;
        if ($student->school_id !== $school->id) {
            abort(403, 'Unauthorized access to this student.');
        }

        $student->user->delete(); // Soft delete user
        $student->delete(); // Soft delete student

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }
}
