<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicSessionController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $sessions = AcademicSession::where('school_id', $school->id)
            ->withCount('terms')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('sessions.index', compact('sessions', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $school = Auth::user()->school;

        // If marked as current, unset others
        if ($request->is_current) {
            AcademicSession::where('school_id', $school->id)->update(['is_current' => false]);
        }

        AcademicSession::create([
            'school_id' => $school->id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_current' => $request->boolean('is_current'),
        ]);

        return redirect()->back()->with('success', 'Academic session created successfully!');
    }

    public function update(Request $request, $id)
    {
        $session = AcademicSession::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $school = Auth::user()->school;

        if ($request->boolean('is_current')) {
            AcademicSession::where('school_id', $school->id)->where('id', '!=', $id)->update(['is_current' => false]);
        }

        $session->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_current' => $request->boolean('is_current'),
        ]);

        return redirect()->back()->with('success', 'Academic session updated successfully!');
    }

    public function destroy($id)
    {
        $session = AcademicSession::findOrFail($id);
        $session->delete();

        return redirect()->back()->with('success', 'Academic session deleted successfully!');
    }
}
