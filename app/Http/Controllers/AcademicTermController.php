<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerm;
use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicTermController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $terms = AcademicTerm::where('school_id', $school->id)
            ->with('session')
            ->orderBy('start_date', 'desc')
            ->get();
        $sessions = AcademicSession::where('school_id', $school->id)->orderBy('start_date', 'desc')->get();

        return view('terms.index', compact('terms', 'sessions', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:academic_sessions,id',
            'name' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $school = Auth::user()->school;

        if ($request->boolean('is_current')) {
            AcademicTerm::where('school_id', $school->id)->update(['is_current' => false]);
        }

        AcademicTerm::create([
            'school_id' => $school->id,
            'session_id' => $request->session_id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_current' => $request->boolean('is_current'),
        ]);

        return redirect()->back()->with('success', 'Academic term created successfully!');
    }

    public function update(Request $request, $id)
    {
        $term = AcademicTerm::findOrFail($id);
        $request->validate([
            'session_id' => 'required|exists:academic_sessions,id',
            'name' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $school = Auth::user()->school;
        if ($request->boolean('is_current')) {
            AcademicTerm::where('school_id', $school->id)->where('id', '!=', $id)->update(['is_current' => false]);
        }

        $term->update([
            'session_id' => $request->session_id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_current' => $request->boolean('is_current'),
        ]);

        return redirect()->back()->with('success', 'Academic term updated successfully!');
    }

    public function destroy($id)
    {
        AcademicTerm::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Academic term deleted successfully!');
    }
}
