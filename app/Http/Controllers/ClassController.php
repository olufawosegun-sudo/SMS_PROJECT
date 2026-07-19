<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\ClassArm;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $classes = SchoolClass::where('school_id', $school->id)
            ->with(['arms' => function($q) {
                $q->withCount('students');
            }, 'arms.teacher.user'])
            ->withCount(['students', 'arms'])
            ->orderBy('name')
            ->get();
        $teachers = Staff::where('school_id', $school->id)->where('staff_type', 'Teacher')->where('status', 'active')->with('user')->get();

        return view('classes.index', compact('classes', 'teachers', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'level' => 'required|string',
            'description' => 'nullable|string|max:255',
        ]);

        $school = Auth::user()->school;

        SchoolClass::create([
            'school_id' => $school->id,
            'name' => $request->name,
            'level' => $request->level,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Class created successfully!');
    }

    public function update(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:50',
            'level' => 'required|string',
            'description' => 'nullable|string|max:255',
        ]);

        $class->update($request->only('name', 'level', 'description'));
        return redirect()->back()->with('success', 'Class updated successfully!');
    }

    public function destroy($id)
    {
        SchoolClass::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Class deleted successfully!');
    }

    /**
     * Get class arms for a specific class (AJAX endpoint)
     */
    public function getArms($classId)
    {
        $school = Auth::user()->school;
        
        $arms = ClassArm::where('class_id', $classId)
            ->whereHas('schoolClass', function($query) use ($school) {
                $query->where('school_id', $school->id);
            })
            ->where('status', 'active')
            ->select('id', 'name', 'class_id', 'capacity')
            ->withCount('students')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'arms' => $arms
        ]);
    }

    /**
     * Store a new class arm
     */
    public function storeArm(Request $request)
    {
        $school = Auth::user()->school;
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:10',
            'capacity' => 'required|integer|min:1|max:100',
            'teacher_id' => 'nullable|exists:staff,id',
        ]);

        // Verify class belongs to school
        $class = SchoolClass::where('id', $request->class_id)
            ->where('school_id', $school->id)
            ->firstOrFail();

        ClassArm::create([
            'school_id' => $school->id,
            'class_id' => $request->class_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
            'teacher_id' => $request->teacher_id,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Class arm created successfully!');
    }

    /**
     * Update a class arm
     */
    public function updateArm(Request $request, $armId)
    {
        $school = Auth::user()->school;
        
        $request->validate([
            'name' => 'required|string|max:10',
            'capacity' => 'required|integer|min:1|max:100',
            'teacher_id' => 'nullable|exists:staff,id',
        ]);

        $arm = ClassArm::where('id', $armId)
            ->where('school_id', $school->id)
            ->firstOrFail();

        $arm->update([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->back()->with('success', 'Class arm updated successfully!');
    }

    /**
     * Delete a class arm
     */
    public function destroyArm($armId)
    {
        $school = Auth::user()->school;

        $arm = ClassArm::where('id', $armId)
            ->where('school_id', $school->id)
            ->firstOrFail();

        // Check if arm has students
        if ($arm->students()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete arm with enrolled students!');
        }

        $arm->delete();

        return redirect()->back()->with('success', 'Class arm deleted successfully!');
    }
}
