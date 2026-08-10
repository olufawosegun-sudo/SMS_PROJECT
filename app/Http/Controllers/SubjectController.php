<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $subjects = Subject::where('school_id', $school->id)->orderBy('name')->get();

        return view('subjects.index', compact('subjects', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20',
            'category' => 'nullable|string|max:50',
            'is_core' => 'nullable|boolean',
        ]);

        $school = Auth::user()->school;

        Subject::create([
            'school_id' => $school->id,
            'name' => $request->name,
            'code' => $request->code,
            'category' => $request->category,
            'is_core' => $request->boolean('is_core'),
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Subject created successfully!');
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20',
            'category' => 'nullable|string|max:50',
        ]);

        $subject->update([
            'name' => $request->name,
            'code' => $request->code,
            'category' => $request->category,
            'is_core' => $request->boolean('is_core'),
        ]);

        return redirect()->back()->with('success', 'Subject updated successfully!');
    }

    public function destroy($id)
    {
        Subject::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Subject deleted successfully!');
    }
}
