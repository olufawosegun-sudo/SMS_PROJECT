<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use App\Models\TeacherSubject;
use App\Mail\TeacherWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index()
    {
        $school = Auth::user()->school;
        
        // Get all staff members with staff_type = 'Teacher'
        $teachers = Staff::where('school_id', $school->id)
            ->where('staff_type', 'Teacher')
            ->with([
                'user', 
                'department', 
                'teacherSubjects' => function($query) use ($school) {
                    $query->where('school_id', $school->id);
                },
                'teacherSubjects.schoolClass',
                'teacherSubjects.subject'
            ])
            ->latest()
            ->paginate(20);

        $stats = [
            'active' => Staff::where('school_id', $school->id)
                ->where('staff_type', 'Teacher')
                ->where('status', 'active')
                ->count(),
            'inactive' => Staff::where('school_id', $school->id)
                ->where('staff_type', 'Teacher')
                ->where('status', 'inactive')
                ->count(),
            'departments' => Department::where('school_id', $school->id)->count(),
        ];

        return view('teachers.index', compact('teachers', 'stats', 'school'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        $school = Auth::user()->school;
        $departments = Department::where('school_id', $school->id)->get();
        $classes = SchoolClass::where('school_id', $school->id)->get();
        $subjects = Subject::where('school_id', $school->id)->get();
        
        // Get current session and term
        $currentSession = AcademicSession::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();
        $currentTerm = AcademicTerm::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();

        return view('teachers.create', compact('departments', 'classes', 'subjects', 'currentSession', 'currentTerm', 'school'));
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(Request $request)
    {
        $school = Auth::user()->school;
        
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'department_id' => [
                'nullable',
                Rule::exists('departments', 'id')->where('school_id', $school->id)
            ],
            'qualification' => ['nullable', 'string', 'max:255'],
            'employment_date' => ['nullable', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'subject_assignments' => ['nullable', 'array'],
            'subject_assignments.*.class_id' => [
                'required_with:subject_assignments',
                Rule::exists('classes', 'id')->where('school_id', $school->id)
            ],
            'subject_assignments.*.subject_id' => [
                'required_with:subject_assignments',
                Rule::exists('subjects', 'id')->where('school_id', $school->id)
            ],
        ]);
        $teacherRole = Role::where('school_id', $school->id)
            ->where('name', 'Teacher')
            ->first();

        // Get current session and term
        $currentSession = AcademicSession::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();
        $currentTerm = AcademicTerm::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();

        DB::beginTransaction();
        try {
            // Handle profile photo upload
            $photoPath = null;
            if ($request->hasFile('profile_photo')) {
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            // Create User account
            $user = User::create([
                'uuid' => (string) Str::uuid(),
                'school_id' => $school->id,
                'role_id' => $teacherRole->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'dob' => $validated['date_of_birth'] ?? null,
                'profile_photo' => $photoPath,
                'password' => Hash::make('password123'), // Default password
                'status' => 'active',
            ]);

            // Create Staff profile (replaces Teacher)
            $staff = Staff::create([
                'school_id' => $school->id,
                'user_id' => $user->id,
                'department_id' => $validated['department_id'] ?? null,
                'staff_no' => 'TCH' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'staff_type' => 'Teacher',
                'qualification' => $validated['qualification'] ?? null,
                'employment_date' => $validated['employment_date'] ?? now(),
                'salary' => $validated['salary'] ?? null,
                'status' => 'active',
            ]);

            // Create subject assignments
            if (!empty($validated['subject_assignments'])) {
                foreach ($validated['subject_assignments'] as $assignment) {
                    TeacherSubject::create([
                        'school_id' => $school->id,
                        'staff_id' => $staff->id, // Changed from teacher_id
                        'class_id' => $assignment['class_id'],
                        'subject_id' => $assignment['subject_id'],
                        'session_id' => $currentSession?->id,
                        'term_id' => $currentTerm?->id,
                    ]);
                }
            }

            // Send welcome email with login credentials
            try {
                Mail::to($user->email)->send(new TeacherWelcomeMail($staff, 'password123'));
            } catch (\Exception $e) {
                // Log email error but don't fail the transaction
                \Log::error('Failed to send welcome email to teacher: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher added successfully! Welcome email sent to ' . $user->email);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create teacher: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified teacher.
     */
    public function show($id)
    {
        $school = Auth::user()->school;
        
        // Find staff member
        $teacher = Staff::where('school_id', $school->id)
            ->where('staff_type', 'Teacher')
            ->where('id', $id)
            ->with(['user', 'department', 'teacherSubjects.schoolClass', 'teacherSubjects.subject'])
            ->firstOrFail();

        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit($id)
    {
        $school = Auth::user()->school;
        
        // Find staff member
        $teacher = Staff::where('school_id', $school->id)
            ->where('staff_type', 'Teacher')
            ->where('id', $id)
            ->with('user', 'teacherSubjects.schoolClass', 'teacherSubjects.subject')
            ->firstOrFail();

        $departments = Department::where('school_id', $school->id)->get();
        $classes = SchoolClass::where('school_id', $school->id)->get();
        $subjects = Subject::where('school_id', $school->id)->get();
        
        // Get current session and term
        $currentSession = AcademicSession::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();
        
        $currentTerm = AcademicTerm::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();

        return view('teachers.edit', compact('teacher', 'departments', 'school', 'classes', 'subjects', 'currentSession', 'currentTerm'));
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(Request $request, $id)
    {
        $school = Auth::user()->school;
        
        // Find staff member
        $teacher = Staff::where('school_id', $school->id)
            ->where('staff_type', 'Teacher')
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $teacher->user_id],
            'phone' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'in:male,female'],
            'dob' => ['nullable', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'employment_date' => ['nullable', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $teacher->user->update(['profile_photo' => $photoPath]);
        }

        // Update User account
        $teacher->user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'status' => $validated['status'],
        ]);

        // Update Staff profile (replaces Teacher)
        $teacher->update([
            'department_id' => $validated['department_id'] ?? null,
            'qualification' => $validated['qualification'] ?? null,
            'employment_date' => $validated['employment_date'] ?? null,
            'salary' => $validated['salary'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher updated successfully!');
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy($id)
    {
        $school = Auth::user()->school;
        
        // Find staff member
        $teacher = Staff::where('school_id', $school->id)
            ->where('staff_type', 'Teacher')
            ->where('id', $id)
            ->firstOrFail();

        $teacher->user->delete(); // Soft delete user (cascades to staff)
        $teacher->delete(); // Soft delete teacher

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully!');
    }
}
