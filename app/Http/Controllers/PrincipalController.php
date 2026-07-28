<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\SchoolBranch;
use App\Mail\PrincipalWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class PrincipalController extends Controller
{
    /**
     * Display a listing of principals.
     */
    public function index()
    {
        $school = Auth::user()->school;
        
        // Get all staff members who are principals
        $principals = Staff::where('school_id', $school->id)
            ->whereIn('staff_type', ['Principal', 'Vice Principal', 'Assistant Principal'])
            ->with(['user', 'department', 'schoolBranch', 'user.schoolBranch'])
            ->latest()
            ->paginate(20);

        $stats = [
            'principal' => Staff::where('school_id', $school->id)
                ->where('staff_type', 'Principal')
                ->count(),
            'vice_principal' => Staff::where('school_id', $school->id)
                ->where('staff_type', 'Vice Principal')
                ->count(),
            'assistant_principal' => Staff::where('school_id', $school->id)
                ->where('staff_type', 'Assistant Principal')
                ->count(),
        ];

        return view('principals.index', compact('principals', 'stats', 'school'));
    }

    /**
     * Show the form for creating a new principal.
     */
    public function create()
    {
        $school = Auth::user()->school;
        $branches = SchoolBranch::where('school_id', $school->id)->where('status', 'active')->get();
        return view('principals.create', compact('branches', 'school'));
    }

    /**
     * Store a newly created principal in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'role_type' => ['required', 'in:Principal,Vice Principal,Assistant Principal'],
            'department_id' => ['nullable', Rule::exists('departments', 'id')],
            'qualification' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'employment_date' => ['nullable', 'date'],
            'years_of_experience' => ['nullable', 'integer', 'min:0'],
            'previous_school' => ['nullable', 'string', 'max:255'],
            'office_location' => ['nullable', 'string', 'max:255'],
            'emergency_contact' => ['nullable', 'string', 'max:50'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:100'],
            'contract_type' => ['nullable', 'in:permanent,contract,temporary'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'school_branch_id' => ['nullable', 'exists:school_branches,id'],
            'custom_branch' => ['nullable', 'string', 'max:150'],
        ]);

        $school = Auth::user()->school;
        $principalRole = Role::where('school_id', $school->id)
            ->where('name', $validated['role_type'])
            ->first();

        // Check if role exists
        if (!$principalRole) {
            return back()->withInput()->withErrors([
                'role_type' => 'The ' . $validated['role_type'] . ' role does not exist for your school. Please contact support.'
            ]);
        }

        // Resolve branch ID if user typed a custom branch name
        $branchId = $validated['school_branch_id'] ?? null;
        if (!$branchId && !empty($validated['custom_branch'])) {
            $branch = SchoolBranch::firstOrCreate([
                'school_id' => $school->id,
                'name' => trim($validated['custom_branch']),
            ]);
            $branchId = $branch->id;
        }

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
                'school_branch_id' => $branchId,
                'role_id' => $principalRole->id,
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

            // Create Staff profile (replaces Principal)
            $staff = Staff::create([
                'school_id' => $school->id,
                'school_branch_id' => $branchId,
                'user_id' => $user->id,
                'department_id' => $validated['department_id'] ?? null,
                'staff_no' => 'PRI' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'staff_type' => $validated['role_type'], // Principal, Vice Principal, or Assistant Principal
                'qualification' => $validated['qualification'] ?? null,
                'specialization' => $validated['specialization'] ?? null,
                'employment_date' => $validated['employment_date'] ?? now(),
                'years_of_experience' => $validated['years_of_experience'] ?? null,
                'previous_employer' => $validated['previous_school'] ?? null,
                'office_location' => $validated['office_location'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact'] ?? null,
                'emergency_contact_relationship' => $validated['emergency_contact_relationship'] ?? null,
                'contract_type' => $validated['contract_type'] ?? 'permanent',
                'salary' => $validated['salary'] ?? null,
                'status' => 'active',
            ]);

            // Send welcome email to principal
            try {
                Mail::to($user->email)->send(new PrincipalWelcomeMail($staff, 'password123'));
            } catch (\Exception $mailException) {
                // Log email error but don't fail the creation
                \Log::error('Failed to send principal welcome email: ' . $mailException->getMessage());
            }

            DB::commit();

            return redirect()->route('principals.index')
                ->with('success', $validated['role_type'] . ' added successfully! Default password: password123');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create principal: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified principal.
     */
    public function show($id)
    {
        $school = Auth::user()->school;
        
        // Find staff member
        $staff = Staff::where('school_id', $school->id)
            ->whereIn('staff_type', ['Principal', 'Vice Principal', 'Assistant Principal'])
            ->with(['user', 'department'])
            ->findOrFail($id);

        return view('principals.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified principal.
     */
    public function edit($id)
    {
        $school = Auth::user()->school;
        
        // Find staff member
        $staff = Staff::where('school_id', $school->id)
            ->whereIn('staff_type', ['Principal', 'Vice Principal', 'Assistant Principal'])
            ->with(['user', 'department'])
            ->findOrFail($id);

        $departments = Department::where('school_id', $school->id)->get();

        return view('principals.edit', compact('staff', 'school', 'departments'));
    }

    /**
     * Update the specified principal in storage.
     */
    public function update(Request $request, $id)
    {
        $school = Auth::user()->school;
        
        // Find staff member
        $staff = Staff::where('school_id', $school->id)
            ->whereIn('staff_type', ['Principal', 'Vice Principal', 'Assistant Principal'])
            ->with('user')
            ->findOrFail($id);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $staff->user_id],
            'phone' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'role_type' => ['required', 'in:Principal,Vice Principal,Assistant Principal'],
            'department_id' => ['nullable', Rule::exists('departments', 'id')],
            'qualification' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'employment_date' => ['nullable', 'date'],
            'years_of_experience' => ['nullable', 'integer', 'min:0'],
            'previous_school' => ['nullable', 'string', 'max:255'],
            'office_location' => ['nullable', 'string', 'max:255'],
            'emergency_contact' => ['nullable', 'string', 'max:50'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:100'],
            'contract_type' => ['nullable', 'in:permanent,contract,temporary'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $principalRole = Role::where('school_id', $school->id)
            ->where('name', $validated['role_type'])
            ->first();

        DB::beginTransaction();
        try {
            // Handle profile photo upload
            $photoPath = $staff->user->profile_photo;
            if ($request->hasFile('profile_photo')) {
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            // Update User account
            $staff->user->update([
                'role_id' => $principalRole->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'dob' => $validated['date_of_birth'] ?? null,
                'profile_photo' => $photoPath,
                'status' => $validated['status'],
            ]);

            // Update Staff profile
            $staff->update([
                'staff_type' => $validated['role_type'],
                'department_id' => $validated['department_id'] ?? null,
                'qualification' => $validated['qualification'] ?? null,
                'specialization' => $validated['specialization'] ?? null,
                'employment_date' => $validated['employment_date'] ?? null,
                'years_of_experience' => $validated['years_of_experience'] ?? null,
                'previous_employer' => $validated['previous_school'] ?? null,
                'office_location' => $validated['office_location'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact'] ?? null,
                'emergency_contact_relationship' => $validated['emergency_contact_relationship'] ?? null,
                'contract_type' => $validated['contract_type'] ?? 'permanent',
                'salary' => $validated['salary'] ?? null,
                'status' => $validated['status'],
            ]);

            DB::commit();

            return redirect()->route('principals.index')
                ->with('success', 'Principal updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update principal: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified principal from storage.
     */
    public function destroy($id)
    {
        $school = Auth::user()->school;
        
        // Find staff member
        $staff = Staff::where('school_id', $school->id)
            ->whereIn('staff_type', ['Principal', 'Vice Principal', 'Assistant Principal'])
            ->with('user')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            // Soft delete the user account (cascades to staff via database)
            $staff->user->delete();
            
            // Also soft delete the staff record
            $staff->delete();

            DB::commit();

            return redirect()->route('principals.index')
                ->with('success', 'Principal deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete principal: ' . $e->getMessage()]);
        }
    }
}
