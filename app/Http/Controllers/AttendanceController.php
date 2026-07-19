<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\SchoolClass;
use App\Models\ClassArm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::user()->school;
        $classes = SchoolClass::where('school_id', $school->id)->with('arms')->orderBy('name')->get();
        $selectedDate = $request->get('date', now()->toDateString());
        $selectedClassId = $request->get('class_id');

        $attendanceQuery = StudentAttendance::where('school_id', $school->id)
            ->where('attendance_date', $selectedDate)
            ->with(['student.user', 'student.schoolClass']);

        if ($selectedClassId) {
            $attendanceQuery->where('class_id', $selectedClassId);
        }

        $attendance = $attendanceQuery->orderBy('created_at', 'desc')->get();

        // Students for marking attendance
        $studentsQuery = Student::where('school_id', $school->id)->where('status', 'active')->with(['user', 'schoolClass', 'arm']);
        if ($selectedClassId) {
            $studentsQuery->where('class_id', $selectedClassId);
        }
        $students = $studentsQuery->orderBy('class_id')->get();

        // Summary stats
        $summary = [
            'total' => $attendance->count(),
            'present' => $attendance->where('status', 'present')->count(),
            'absent' => $attendance->where('status', 'absent')->count(),
            'late' => $attendance->where('status', 'late')->count(),
        ];

        return view('attendance.index', compact('classes', 'attendance', 'students', 'selectedDate', 'selectedClassId', 'summary', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'students' => 'required|array',
            'students.*.student_id' => 'required|exists:students,id',
            'students.*.status' => 'required|in:present,absent,late',
        ]);

        $school = Auth::user()->school;

        foreach ($request->students as $entry) {
            $student = Student::findOrFail($entry['student_id']);
            StudentAttendance::updateOrCreate(
                [
                    'school_id' => $school->id,
                    'student_id' => $entry['student_id'],
                    'attendance_date' => $request->attendance_date,
                ],
                [
                    'class_id' => $student->class_id,
                    'session_id' => 1,
                    'term_id' => 1,
                    'attendance_time' => now()->toTimeString(),
                    'status' => $entry['status'],
                    'remark' => $entry['remark'] ?? null,
                    'recorded_by' => Auth::id(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance recorded successfully!');
    }
}
