<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use App\Models\Guardian;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentAttendance;
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

        // Custom stats for Principal/Owner, Student, and Guardian
        $classSummaries = collect();
        $absentStudents = collect();
        $studentStats = null;
        $studentAttendanceHistory = collect();
        $wardsStats = collect(); // For guardians

        if ($userRole === 'Guardian') {
            // Get guardian profile
            $guardian = Guardian::where('school_id', $school->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($guardian) {
                // Get all wards (children) linked to this guardian
                $wards = $guardian->students()->with(['user', 'schoolClass', 'arm'])->get();

                // Get attendance statistics for each ward
                $wardsStats = $wards->map(function ($ward) use ($school) {
                    $attendanceHistory = StudentAttendance::where('school_id', $school->id)
                        ->where('student_id', $ward->id)
                        ->orderBy('attendance_date', 'desc')
                        ->get();

                    $total = $attendanceHistory->count();
                    $present = $attendanceHistory->where('status', 'present')->count();
                    $late = $attendanceHistory->where('status', 'late')->count();
                    $absent = $attendanceHistory->where('status', 'absent')->count();
                    $rate = $total > 0 ? round((($present + ($late * 0.5)) / $total) * 100, 1) : 0;

                    return [
                        'student' => $ward,
                        'total' => $total,
                        'present' => $present,
                        'late' => $late,
                        'absent' => $absent,
                        'rate' => $rate,
                        'recent_attendance' => $attendanceHistory->take(10), // Last 10 records
                    ];
                });
            }
        } elseif ($userRole === 'Student') {
            $student = Student::where('school_id', $school->id)->where('user_id', Auth::id())->first();
            if ($student) {
                $studentAttendanceHistory = StudentAttendance::where('school_id', $school->id)
                    ->where('student_id', $student->id)
                    ->orderBy('attendance_date', 'desc')
                    ->get();

                $total = $studentAttendanceHistory->count();
                $present = $studentAttendanceHistory->where('status', 'present')->count();
                $late = $studentAttendanceHistory->where('status', 'late')->count();
                $absent = $studentAttendanceHistory->where('status', 'absent')->count();
                $rate = $total > 0 ? round((($present + ($late * 0.5)) / $total) * 100, 1) : 100.0;

                $studentStats = [
                    'total' => $total,
                    'present' => $present,
                    'late' => $late,
                    'absent' => $absent,
                    'rate' => $rate,
                    'student' => $student,
                ];
            }
        } elseif (in_array($userRole, ['Principal', 'Owner'])) {
            $classSummaries = SchoolClass::where('school_id', $school->id)
                ->withCount(['students' => function ($q) {
                    $q->where('status', 'active');
                }])
                ->orderBy('name')
                ->get()
                ->map(function ($class) use ($school, $selectedDate) {
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
                        'rate' => $rate,
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
            'summary', 'school', 'userRole', 'classSummaries', 'absentStudents',
            'studentStats', 'studentAttendanceHistory', 'wardsStats'
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
