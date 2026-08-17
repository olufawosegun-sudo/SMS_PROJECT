<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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

    /**
     * Set an academic session as the current active session.
     *
     * @param  int  $id  The session ID to set as active
     * @return JsonResponse|RedirectResponse
     */
    public function setActive(Request $request, $id)
    {
        $school = Auth::user()->school;
        $session = AcademicSession::where('school_id', $school->id)->findOrFail($id);

        // Unset all sessions for this school, then set the chosen one
        AcademicSession::where('school_id', $school->id)->update(['is_current' => false]);
        $session->update(['is_current' => true]);

        // Optionally set a term as current too
        if ($request->filled('term_id')) {
            AcademicTerm::where('school_id', $school->id)->update(['is_current' => false]);
            AcademicTerm::where('school_id', $school->id)
                ->where('id', $request->term_id)
                ->update(['is_current' => true]);
        }

        if ($request->wantsJson()) {
            $currentTerm = AcademicTerm::where('school_id', $school->id)->where('is_current', true)->first();

            return response()->json([
                'success' => true,
                'session' => $session->name,
                'term' => $currentTerm?->name ?? 'No term set',
            ]);
        }

        return redirect()->back()->with('success', 'Academic session "'.$session->name.'" is now the active session!');
    }

    public function destroy($id)
    {
        $session = AcademicSession::findOrFail($id);
        $session->delete();

        return redirect()->back()->with('success', 'Academic session deleted successfully!');
    }
}
