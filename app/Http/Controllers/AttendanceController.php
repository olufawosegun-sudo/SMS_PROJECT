<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\SchoolClass;
use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::user()->school;
        $userRole = Auth::user()->role->name;
        $selectedDate = $request->get('date', now()->toDateString());
        $selectedClassId = $request->get('class_id');

        // Both teachers and owners can see all classes in the school
        $classes = SchoolClass::where('school_id', $school->id)
            ->with('arms')
            ->orderBy('name')
            ->get();

        // Query existing attendance records for the selected date
        $attendanceQuery = StudentAttendance::where('school_id', $school->id)
            ->where('attendance_date', $selectedDate)
            ->with(['student.user', 'student.schoolClass']);

        // Query students for the marking form (used by teachers)
        $studentsQuery = Student::where('school_id', $school->id)
            ->where('status', 'active')
            ->with(['user', 'schoolClass', 'arm']);

        // Filter by class if selected
        if ($selectedClassId) {
            $attendanceQuery->where('class_id', $selectedClassId);
            $studentsQuery->where('class_id', $selectedClassId);
        }

        $attendance = $attendanceQuery->orderBy('created_at', 'desc')->get();
        $students = $studentsQuery->orderBy('class_id')->get();

        // Summary stats
        $summary = [
            'total' => $attendance->count(),
            'present' => $attendance->where('status', 'present')->count(),
            'absent' => $attendance->where('status', 'absent')->count(),
            'late' => $attendance->where('status', 'late')->count(),
        ];

        // Custom stats for Principal/Owner
        $classSummaries = collect();
        $absentStudents = collect();

        if (in_array($userRole, ['Principal', 'Owner'])) {
            $classSummaries = SchoolClass::where('school_id', $school->id)
                ->withCount(['students' => function($q) {
                    $q->where('status', 'active');
                }])
                ->orderBy('name')
                ->get()
                ->map(function($class) use ($school, $selectedDate) {
                    $records = StudentAttendance::where('school_id', $school->id)
                        ->where('class_id', $class->id)
                        ->where('attendance_date', $selectedDate)
                        ->get();
                    
                    $total = $class->students_count;
                    $present = $records->where('status', 'present')->count();
                    $late = $records->where('status', 'late')->count();
                    $absent = $records->where('status', 'absent')->count();
                    $markedCount = $records->count();
                    $unmarked = max(0, $total - $markedCount);

                    $rate = $total > 0 ? round((($present + ($late * 0.5)) / $total) * 100) : 100;

                    return [
                        'class_id' => $class->id,
                        'name' => $class->name,
                        'total' => $total,
                        'present' => $present,
                        'late' => $late,
                        'absent' => $absent,
                        'unmarked' => $unmarked,
                        'rate' => $rate
                    ];
                });

            // Get students who were absent or late today
            $absentStudents = StudentAttendance::where('school_id', $school->id)
                ->where('attendance_date', $selectedDate)
                ->whereIn('status', ['absent', 'late'])
                ->with(['student.user', 'student.schoolClass', 'student.guardians.user'])
                ->get();
        }

        return view('attendance.index', compact(
            'classes', 'attendance', 'students', 'selectedDate', 'selectedClassId', 
            'summary', 'school', 'userRole', 'classSummaries', 'absentStudents'
        ));
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

        // Resolve current session and term dynamically
        $currentSession = AcademicSession::where('school_id', $school->id)->where('is_current', true)->first()
            ?? AcademicSession::where('school_id', $school->id)->first();
        $currentTerm = AcademicTerm::where('school_id', $school->id)->where('is_current', true)->first()
            ?? AcademicTerm::where('school_id', $school->id)->first();

        $sessionId = $currentSession ? $currentSession->id : 1;
        $termId = $currentTerm ? $currentTerm->id : 1;

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
                    'session_id' => $sessionId,
                    'term_id' => $termId,
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
