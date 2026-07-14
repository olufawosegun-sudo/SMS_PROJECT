<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PrincipalController extends Controller
{
    /**
     * Display a listing of principals.
     */
    public function index()
    {
        $school = Auth::user()->school;
        $principals = User::where('school_id', $school->id)
            ->whereHas('role', function($query) {
                $query->whereIn('name', ['Principal', 'Vice Principal']);
            })
            ->with('role')
            ->latest()
            ->paginate(20);

        return view('principals.index', compact('principals', 'school'));
    }

    /**
     * Show the form for creating a new principal.
     */
    public function create()
    {
        $school = Auth::user()->school;
        return view('principals.create', compact('school'));
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
            'dob' => ['nullable', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'role_type' => ['required', 'in:Principal,Vice Principal'],
        ]);

        $school = Auth::user()->school;
        $principalRole = Role::where('school_id', $school->id)
            ->where('name', $validated['role_type'])
            ->first();

        // Handle profile photo upload
        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        // Create User account (Principal has no separate profile table)
        User::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $school->id,
            'role_id' => $principalRole->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'profile_photo' => $photoPath,
            'password' => Hash::make('password123'), // Default password
            'status' => 'active',
        ]);

        return redirect()->route('principals.index')
            ->with('success', 'Principal added successfully! Default password: password123');
    }

    /**
     * Display the specified principal.
     */
    public function show(User $principal)
    {
        $principal->load('role');
        return view('principals.show', compact('principal'));
    }

    /**
     * Show the form for editing the specified principal.
     */
    public function edit(User $principal)
    {
        $school = Auth::user()->school;
        return view('principals.edit', compact('principal', 'school'));
    }

    /**
     * Update the specified principal in storage.
     */
    public function update(Request $request, User $principal)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $principal->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'in:male,female'],
            'dob' => ['nullable', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'role_type' => ['required', 'in:Principal,Vice Principal'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $school = Auth::user()->school;
        $principalRole = Role::where('school_id', $school->id)
            ->where('name', $validated['role_type'])
            ->first();

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $principal->update(['profile_photo' => $photoPath]);
        }

        // Update User account
        $principal->update([
            'role_id' => $principalRole->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('principals.index')
            ->with('success', 'Principal updated successfully!');
    }

    /**
     * Remove the specified principal from storage.
     */
    public function destroy(User $principal)
    {
        $principal->delete(); // Soft delete user

        return redirect()->route('principals.index')
            ->with('success', 'Principal deleted successfully!');
    }
}
