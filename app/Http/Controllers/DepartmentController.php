<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $school = Auth::user()->school;
        $departments = Department::where('school_id', $school->id)
            ->withCount('teachers')
            ->latest()
            ->paginate(15);

        return view('departments.index', compact('departments', 'school'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $school = Auth::user()->school;
        return view('departments.create', compact('school'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $school = Auth::user()->school;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Department::create([
            'school_id' => $school->id,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $school = Auth::user()->school;
        if ($department->school_id !== $school->id) {
            abort(403, 'Unauthorized action.');
        }

        $department->load(['teachers.user']);
        return view('departments.show', compact('department', 'school'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $school = Auth::user()->school;
        if ($department->school_id !== $school->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('departments.edit', compact('department', 'school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $school = Auth::user()->school;
        if ($department->school_id !== $school->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $department->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $school = Auth::user()->school;
        if ($department->school_id !== $school->id) {
            abort(403, 'Unauthorized action.');
        }

        // Dissociate teachers from this department before deleting it
        $department->teachers()->update(['department_id' => null]);
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully!');
    }
}
