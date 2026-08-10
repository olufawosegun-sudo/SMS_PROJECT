<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use App\Models\SchoolClass;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::user()->school;
        $classes = SchoolClass::where('school_id', $school->id)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $school->id)->orderBy('name')->get();
        $teachers = Staff::where('school_id', $school->id)->where('staff_type', 'Teacher')->where('status', 'active')->with('user')->get();

        $selectedClassId = $request->get('class_id');

        $timetableQuery = Timetable::where('school_id', $school->id)
            ->with(['schoolClass', 'subject', 'teacher.user']);

        if ($selectedClassId) {
            $timetableQuery->where('class_id', $selectedClassId);
        }

        $timetable = $timetableQuery->orderByRaw("FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday')")
            ->orderBy('start_time')
            ->get();

        // Group by day for display
        $timetableByDay = $timetable->groupBy('day');

        return view('timetables.index', compact('classes', 'subjects', 'teachers', 'timetable', 'timetableByDay', 'selectedClassId', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:staffs,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $school = Auth::user()->school;

        Timetable::create([
            'school_id' => $school->id,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'session_id' => AcademicSession::where('school_id', $school->id)->where('is_current', true)->value('id') ?? 1,
            'term_id' => AcademicTerm::where('school_id', $school->id)->where('is_current', true)->value('id') ?? 1,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->back()->with('success', 'Timetable entry added successfully!');
    }

    public function destroy($id)
    {
        Timetable::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Timetable entry deleted successfully!');
    }
}
