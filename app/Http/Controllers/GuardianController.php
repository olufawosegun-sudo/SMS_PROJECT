<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Mail\GuardianWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class GuardianController extends Controller
{
    /**
     * Display a listing of guardians.
     */
    public function index()
    {
        $school = Auth::user()->school;
        $guardians = Guardian::where('school_id', $school->id)
            ->with(['user', 'students'])
            ->latest()
            ->paginate(20);

        $stats = [
            'active' => Guardian::where('school_id', $school->id)->where('status', 'active')->count(),
            'inactive' => Guardian::where('school_id', $school->id)->where('status', 'inactive')->count(),
            'total_students' => Student::where('school_id', $school->id)->count(),
        ];

        return view('guardians.index', compact('guardians', 'stats', 'school'));
    }

    /**
     * Show the form for creating a new guardian.
     */
    public function create()
    {
        $school = Auth::user()->school;
        $students = Student::where('school_id', $school->id)
            ->with('user')
            ->get();

        return view('guardians.create', compact('students', 'school'));
    }

    /**
     * Store a newly created guardian in storage.
     */
    public function store(Request $request)
    {
        $school = Auth::user()->school;
        
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:50'],
            'gender' => ['nullable', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'relationship' => ['required', 'string', 'max:50'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => [
                Rule::exists('students', 'id')->where('school_id', $school->id)
            ],
        ]);
        $guardianRole = Role::where('school_id', $school->id)
            ->where('name', 'Guardian')
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
            'role_id' => $guardianRole->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'profile_photo' => $photoPath,
            'password' => Hash::make('password123'), // Default password
            'status' => 'active',
        ]);

        // Create Guardian profile
        $guardian = Guardian::create([
            'school_id' => $school->id,
            'user_id' => $user->id,
            'occupation' => $validated['occupation'] ?? null,
            'address' => $validated['address'] ?? null,
            'relationship' => $validated['relationship'],
            'status' => 'active',
        ]);

        // Link students to guardian
        if (!empty($validated['student_ids'])) {
            $guardian->students()->attach($validated['student_ids']);
        }

        // Send welcome email with default password
        try {
            Mail::to($user->email)->send(new GuardianWelcomeMail($guardian, 'password123'));
        } catch (\Exception $e) {
            // Log error but don't fail the creation
            \Log::error('Failed to send guardian welcome email: ' . $e->getMessage());
        }

        return redirect()->route('guardians.index')
            ->with('success', 'Parent/Guardian added successfully! Welcome email sent with default password: password123');
    }

    /**
     * Display the specified guardian.
     */
    public function show(Guardian $guardian)
    {
        // SECURITY: Verify guardian belongs to current user's school
        $school = Auth::user()->school;
        if ($guardian->school_id !== $school->id) {
            abort(403, 'Unauthorized access to this guardian.');
        }

        $guardian->load(['user', 'students.user', 'students.schoolClass']);
        return view('guardians.show', compact('guardian'));
    }

    /**
     * Show the form for editing the specified guardian.
     */
    public function edit(Guardian $guardian)
    {
        // SECURITY: Verify guardian belongs to current user's school
        $school = Auth::user()->school;
        if ($guardian->school_id !== $school->id) {
            abort(403, 'Unauthorized access to this guardian.');
        }

        $students = Student::where('school_id', $school->id)
            ->with('user')
            ->get();
        $guardian->load(['user', 'students']);

        return view('guardians.edit', compact('guardian', 'students', 'school'));
    }

    /**
     * Update the specified guardian in storage.
     */
    public function update(Request $request, Guardian $guardian)
    {
        // SECURITY: Verify guardian belongs to current user's school
        $school = Auth::user()->school;
        if ($guardian->school_id !== $school->id) {
            abort(403, 'Unauthorized access to this guardian.');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $guardian->user_id],
            'phone' => ['required', 'string', 'max:50'],
            'gender' => ['nullable', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'relationship' => ['required', 'string', 'max:50'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => [
                Rule::exists('students', 'id')->where('school_id', $school->id)
            ],
            'status' => ['required', 'in:active,inactive'],
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $guardian->user->update(['profile_photo' => $photoPath]);
        }

        // Update User account
        $guardian->user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'status' => $validated['status'],
        ]);

        // Update Guardian profile
        $guardian->update([
            'occupation' => $validated['occupation'] ?? null,
            'address' => $validated['address'] ?? null,
            'relationship' => $validated['relationship'],
            'status' => $validated['status'],
        ]);

        // Update student links (only students from the same school)
        if (!empty($validated['student_ids'])) {
            // SECURITY: Verify all student_ids belong to the same school
            $validStudentIds = Student::where('school_id', $school->id)
                ->whereIn('id', $validated['student_ids'])
                ->pluck('id')
                ->toArray();
            
            $guardian->students()->sync($validStudentIds);
        } else {
            $guardian->students()->detach();
        }

        return redirect()->route('guardians.index')
            ->with('success', 'Parent/Guardian updated successfully!');
    }

    /**
     * Remove the specified guardian from storage.
     */
    public function destroy(Guardian $guardian)
    {
        // SECURITY: Verify guardian belongs to current user's school
        $school = Auth::user()->school;
        if ($guardian->school_id !== $school->id) {
            abort(403, 'Unauthorized access to this guardian.');
        }

        $guardian->students()->detach(); // Remove student links
        $guardian->user->delete(); // Soft delete user
        $guardian->delete(); // Soft delete guardian

        return redirect()->route('guardians.index')
            ->with('success', 'Parent/Guardian deleted successfully!');
    }
}
