<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\SchoolBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $announcements = Announcement::where('school_id', $school->id)
            ->with('schoolBranch')
            ->orderBy('created_at', 'desc')
            ->get();
        $branches = SchoolBranch::where('school_id', $school->id)->where('status', 'active')->get();

        return view('announcements.index', compact('announcements', 'branches', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'audience' => 'required|in:all,teachers,students,guardians',
            'priority' => 'required|in:low,normal,high,urgent',
            'school_branch_id' => 'nullable|exists:school_branches,id',
        ]);

        $school = Auth::user()->school;

        Announcement::create([
            'school_id' => $school->id,
            'school_branch_id' => $request->school_branch_id,
            'title' => $request->title,
            'content' => $request->content,
            'audience' => $request->audience,
            'priority' => $request->priority,
            'published_by' => Auth::id(),
            'published_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Announcement published successfully!');
    }

    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Announcement deleted successfully!');
    }
}
