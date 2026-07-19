<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Staff;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\StudentAttendance;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use App\Models\TeacherSubject;
use App\Models\StudentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the role-based dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $school = $user->school;
        $role = $user->role->name ?? 'Owner';

        // Route to appropriate dashboard based on role
        switch ($role) {
            case 'Owner':
                return $this->ownerDashboard($user, $school);
            case 'Principal':
            case 'Vice Principal':
            case 'Assistant Principal':
                return $this->principalDashboard($user, $school);
            case 'Teacher':
                return $this->teacherDashboard($user, $school);
            case 'Guardian':
                return $this->guardianDashboard($user, $school);
            case 'Student':
                return $this->studentDashboard($user, $school);
            default:
                return $this->ownerDashboard($user, $school);
        }
    }

    /**
     * Owner/Principal Dashboard - Full Access
     */
    private function ownerDashboard($user, $school)
    {
        // Comprehensive statistics for owner dashboard
        $stats = [
            'total_students' => Student::where('school_id', $school->id)->where('status', 'active')->count(),
            'total_teachers' => Staff::where('school_id', $school->id)->where('staff_type', 'Teacher')->where('status', 'active')->count(),
            'total_guardians' => \App\Models\Guardian::where('school_id', $school->id)->where('status', 'active')->count(),
            'total_classes' => SchoolClass::where('school_id', $school->id)->count(),
            'total_subjects' => \App\Models\Subject::where('school_id', $school->id)->count(),
            'total_departments' => \App\Models\Department::where('school_id', $school->id)->count(),
            'total_principals' => Staff::where('school_id', $school->id)
                ->whereIn('staff_type', ['Principal', 'Vice Principal', 'Assistant Principal'])
                ->where('status', 'active')
                ->count(),
        ];

        // Today's attendance
        $todayAttendance = StudentAttendance::where('school_id', $school->id)
            ->whereDate('attendance_date', today())
            ->selectRaw('COUNT(*) as total, 
                        SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
                        SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent,
                        SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late')
            ->first();

        $stats['today_attendance'] = [
            'total' => $todayAttendance->total ?? 0,
            'present' => $todayAttendance->present ?? 0,
            'absent' => $todayAttendance->absent ?? 0,
            'late' => $todayAttendance->late ?? 0,
            'rate' => $todayAttendance->total > 0 
                ? round((($todayAttendance->present + ($todayAttendance->late * 0.5)) / $todayAttendance->total) * 100) 
                : 0
        ];

        // Financial statistics (mock data for now - will be real when finance tables are ready)
        $stats['total_revenue'] = 0; // Sum of all payments
        $stats['outstanding_fees'] = 0; // Pending invoices
        $stats['total_expenses'] = 0; // Sum of expenses

        // Academic session and term
        $currentSession = AcademicSession::where('school_id', $school->id)->where('is_current', true)->first();
        $currentTerm = AcademicTerm::where('school_id', $school->id)->where('is_current', true)->first();

        // Weekly attendance trend (last 7 days)
        $attendanceStats = StudentAttendance::where('school_id', $school->id)
            ->selectRaw('attendance_date, 
                        COUNT(*) as total, 
                        SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
                        SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late')
            ->where('attendance_date', '>=', now()->subDays(7))
            ->groupBy('attendance_date')
            ->orderBy('attendance_date', 'asc')
            ->get();

        $attendanceData = [];
        $attendanceLabels = [];

        if ($attendanceStats->isEmpty()) {
            // Fallback mock data
            $attendanceData = [92, 88, 95, 90, 87, 93, 91];
            $attendanceLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        } else {
            foreach ($attendanceStats as $stat) {
                $rate = $stat->total > 0 ? round((($stat->present + ($stat->late * 0.5)) / $stat->total) * 100) : 0;
                $attendanceData[] = $rate;
                $attendanceLabels[] = date('D', strtotime($stat->attendance_date));
            }
        }

        // Student enrollment trend (last 6 months)
        $enrollmentTrend = Student::where('school_id', $school->id)
            ->selectRaw('DATE_FORMAT(admission_date, "%Y-%m") as month, COUNT(*) as count')
            ->where('admission_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $enrollmentData = [];
        $enrollmentLabels = [];
        foreach ($enrollmentTrend as $item) {
            $enrollmentData[] = $item->count;
            $enrollmentLabels[] = date('M', strtotime($item->month . '-01'));
        }

        // Gender distribution
        $genderStats = Student::where('students.school_id', $school->id)
            ->selectRaw('
                SUM(CASE WHEN users.gender = "male" THEN 1 ELSE 0 END) as male,
                SUM(CASE WHEN users.gender = "female" THEN 1 ELSE 0 END) as female
            ')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->first();

        // Classes and Class Arms data
        $classesWithArms = SchoolClass::where('school_id', $school->id)
            ->with(['arms' => function($q) {
                $q->withCount('students');
            }])
            ->withCount('students')
            ->orderBy('name')
            ->get();

        // Get notifications
        $notifications = \App\Models\Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get announcements
        $announcements = Announcement::where('school_id', $school->id)
            ->where('status', 'active')
            ->orderBy('announced_at', 'desc')
            ->limit(5)
            ->get();

        // Recent activities - combination of different events
        $recentActivities = [];

        // Recent students (last 5)
        $recentStudents = Student::where('school_id', $school->id)
            ->with(['user', 'schoolClass'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentStudents as $student) {
            $recentActivities[] = [
                'icon' => 'user-add',
                'title' => 'New Student Enrolled',
                'description' => $student->user->name . ' joined ' . ($student->schoolClass->name ?? 'N/A'),
                'time' => $student->created_at->diffForHumans(),
                'color' => 'success',
                'type' => 'student',
            ];
        }

        // Recent teachers (last 3)
        $recentTeachers = Staff::where('school_id', $school->id)
            ->where('staff_type', 'Teacher')
            ->with(['user', 'department'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentTeachers as $teacher) {
            $recentActivities[] = [
                'icon' => 'briefcase',
                'title' => 'New Teacher Added',
                'description' => $teacher->user->name . ' joined as ' . ($teacher->department->name ?? 'teaching') . ' staff',
                'time' => $teacher->created_at->diffForHumans(),
                'color' => 'info',
                'type' => 'teacher',
            ];
        }

        // Recent guardians (last 3)
        $recentGuardians = \App\Models\Guardian::where('school_id', $school->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentGuardians as $guardian) {
            $recentActivities[] = [
                'icon' => 'users',
                'title' => 'New Parent Registered',
                'description' => $guardian->user->name . ' registered as parent/guardian',
                'time' => $guardian->created_at->diffForHumans(),
                'color' => 'warning',
                'type' => 'guardian',
            ];
        }

        // Recent announcements
        foreach ($announcements->take(2) as $announcement) {
            $recentActivities[] = [
                'icon' => 'megaphone',
                'title' => 'Announcement Published',
                'description' => $announcement->title,
                'time' => $announcement->announced_at->diffForHumans(),
                'color' => 'primary',
                'type' => 'announcement',
            ];
        }

        // Sort all activities by time
        usort($recentActivities, function($a, $b) {
            return strtotime($a['time']) <=> strtotime($b['time']);
        });

        // Take top 15 recent activities
        $recentActivities = array_slice($recentActivities, 0, 15);

        // User activity breakdown
        $userActivityBreakdown = [
            'teachers' => [
                'total' => $stats['total_teachers'],
                'active_today' => Staff::where('school_id', $school->id)
                    ->where('staff_type', 'Teacher')
                    ->whereHas('user', function($q) {
                        $q->where('last_login', '>=', now()->startOfDay());
                    })->count(),
                'recent_activity' => 'Marked attendance, uploaded lessons',
            ],
            'students' => [
                'total' => $stats['total_students'],
                'present_today' => $stats['today_attendance']['present'] ?? 0,
                'recent_activity' => 'Submitted assignments, took attendance',
            ],
            'guardians' => [
                'total' => $stats['total_guardians'],
                'active_this_week' => \App\Models\Guardian::where('school_id', $school->id)
                    ->whereHas('user', function($q) {
                        $q->where('last_login', '>=', now()->subDays(7));
                    })->count(),
                'recent_activity' => 'Viewed reports, made payments',
            ],
            'principals' => [
                'total' => $stats['total_principals'],
                'active_today' => Staff::where('school_id', $school->id)
                    ->whereIn('staff_type', ['Principal', 'Vice Principal', 'Assistant Principal'])
                    ->whereHas('user', function($q) {
                        $q->where('last_login', '>=', now()->startOfDay());
                    })->count(),
                'recent_activity' => 'Approved results, created announcements',
            ],
        ];

        // Quick action stats
        $quickStats = [
            'pending_admissions' => \App\Models\AdmissionApplication::where('school_id', $school->id)->where('status', 'pending')->count(),
            'leave_requests' => 0, // When leave table is ready
            'pending_results' => 0, // When results table is ready
            'upcoming_events' => \App\Models\Event::where('school_id', $school->id)->where('event_date', '>=', now())->count(),
        ];

        // Student Documents Statistics
        $documentStats = [
            'total_documents' => StudentDocument::where('school_id', $school->id)->count(),
            'missing_documents' => Student::where('school_id', $school->id)
                ->where('status', 'active')
                ->whereDoesntHave('documents', function($q) {
                    $q->where('document_type', StudentDocument::TYPE_BIRTH_CERTIFICATE);
                })
                ->count(),
            'expiring_soon' => StudentDocument::where('school_id', $school->id)
                ->where('status', 'active')
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<=', now()->addDays(30))
                ->where('expiry_date', '>', now())
                ->count(),
            'expired' => StudentDocument::where('school_id', $school->id)
                ->where('status', 'active')
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<', now())
                ->count(),
        ];

        // Recent documents (last 10)
        $recentDocuments = StudentDocument::where('school_id', $school->id)
            ->with(['student.user', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.owner', compact(
            'user',
            'school',
            'stats',
            'currentSession',
            'currentTerm',
            'attendanceData',
            'attendanceLabels',
            'enrollmentData',
            'enrollmentLabels',
            'genderStats',
            'classesWithArms',
            'notifications',
            'announcements',
            'recentActivities',
            'quickStats',
            'userActivityBreakdown',
            'documentStats',
            'recentDocuments'
        ));
    }

    /**
     * Teacher Dashboard - Limited to assigned classes and subjects
     */
    private function teacherDashboard($user, $school)
    {
        // Get teacher's assigned classes and subjects
        $teacherSubjects = TeacherSubject::where('school_id', $school->id)
            ->where('staff_id', $user->teacher->id ?? 0)
            ->with(['schoolClass', 'subject'])
            ->get();

        // Teacher-specific statistics
        $stats = [
            'total_classes' => $teacherSubjects->unique('class_id')->count(),
            'total_subjects' => $teacherSubjects->unique('subject_id')->count(),
            'total_students' => 0, // Will be calculated when student-class relationships are ready
            'attendance_rate' => 85, // Mock data for now
        ];

        // Get current session and term
        $currentSession = AcademicSession::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();
        
        $currentTerm = AcademicTerm::where('school_id', $school->id)
            ->where('is_current', true)
            ->first();

        // Today's schedule (mock data - will be real when timetable is integrated)
        $todaySchedule = [];

        // Get teacher's staff record to access assigned students
        $staff = $user->teacher ?? $user->staff;
        $teacherClassIds = $teacherSubjects->pluck('class_id')->unique();

        // Student Documents for teacher's classes (view only)
        $documentStats = [
            'total_students' => Student::where('school_id', $school->id)
                ->whereIn('class_id', $teacherClassIds)
                ->where('status', 'active')
                ->count(),
            'missing_documents' => Student::where('school_id', $school->id)
                ->whereIn('class_id', $teacherClassIds)
                ->where('status', 'active')
                ->whereDoesntHave('documents', function($q) {
                    $q->where('document_type', StudentDocument::TYPE_BIRTH_CERTIFICATE);
                })
                ->count(),
        ];

        // Recent documents from teacher's students
        $recentDocuments = StudentDocument::where('school_id', $school->id)
            ->whereHas('student', function($q) use ($teacherClassIds) {
                $q->whereIn('class_id', $teacherClassIds);
            })
            ->with(['student.user', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.teacher', compact(
            'user', 
            'school', 
            'stats', 
            'teacherSubjects', 
            'currentSession', 
            'currentTerm', 
            'todaySchedule',
            'documentStats',
            'recentDocuments'
        ));
    }

    /**
     * Principal Dashboard - Academic Management (No Financial Access)
     */
    private function principalDashboard($user, $school)
    {
        // Academic-focused statistics (NO financial data)
        $stats = [
            'total_students' => Student::where('school_id', $school->id)->where('status', 'active')->count(),
            'total_teachers' => Staff::where('school_id', $school->id)->where('staff_type', 'Teacher')->where('status', 'active')->count(),
            'total_classes' => SchoolClass::where('school_id', $school->id)->count(),
            'total_subjects' => \App\Models\Subject::where('school_id', $school->id)->count(),
        ];

        // Today's attendance
        $todayAttendance = StudentAttendance::where('school_id', $school->id)
            ->whereDate('attendance_date', today())
            ->selectRaw('COUNT(*) as total, 
                        SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
                        SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent,
                        SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late')
            ->first();

        $stats['today_attendance'] = [
            'total' => $todayAttendance->total ?? 0,
            'present' => $todayAttendance->present ?? 0,
            'absent' => $todayAttendance->absent ?? 0,
            'late' => $todayAttendance->late ?? 0,
            'rate' => $todayAttendance->total > 0 
                ? round((($todayAttendance->present + ($todayAttendance->late * 0.5)) / $todayAttendance->total) * 100) 
                : 0
        ];

        // Academic session and term
        $currentSession = AcademicSession::where('school_id', $school->id)->where('is_current', true)->first();
        $currentTerm = AcademicTerm::where('school_id', $school->id)->where('is_current', true)->first();

        // Weekly attendance trend (last 7 days)
        $attendanceStats = StudentAttendance::where('school_id', $school->id)
            ->selectRaw('attendance_date, 
                        COUNT(*) as total, 
                        SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
                        SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late')
            ->where('attendance_date', '>=', now()->subDays(7))
            ->groupBy('attendance_date')
            ->orderBy('attendance_date', 'asc')
            ->get();

        $attendanceData = [];
        $attendanceLabels = [];

        if ($attendanceStats->isEmpty()) {
            // Fallback mock data
            $attendanceData = [92, 88, 95, 90, 87, 93, 91];
            $attendanceLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        } else {
            foreach ($attendanceStats as $stat) {
                $rate = $stat->total > 0 ? round((($stat->present + ($stat->late * 0.5)) / $stat->total) * 100) : 0;
                $attendanceData[] = $rate;
                $attendanceLabels[] = date('D', strtotime($stat->attendance_date));
            }
        }

        // Gender distribution
        $genderStats = Student::where('students.school_id', $school->id)
            ->selectRaw('
                SUM(CASE WHEN users.gender = "male" THEN 1 ELSE 0 END) as male,
                SUM(CASE WHEN users.gender = "female" THEN 1 ELSE 0 END) as female
            ')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->first();

        // Classes and Class Arms data
        $classesWithArms = SchoolClass::where('school_id', $school->id)
            ->with(['arms' => function($q) {
                $q->withCount('students');
            }])
            ->withCount('students')
            ->orderBy('name')
            ->get();

        // Get notifications
        $notifications = \App\Models\Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get announcements
        $announcements = Announcement::where('school_id', $school->id)
            ->where('status', 'active')
            ->orderBy('announced_at', 'desc')
            ->limit(5)
            ->get();

        // Recent academic activities
        $recentActivities = [];

        // Recent students (last 5)
        $recentStudents = Student::where('school_id', $school->id)
            ->with(['user', 'schoolClass'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentStudents as $student) {
            $recentActivities[] = [
                'icon' => 'user-add',
                'title' => 'New Student Enrolled',
                'description' => $student->user->name . ' joined ' . ($student->schoolClass->name ?? 'N/A'),
                'time' => $student->created_at->diffForHumans(),
                'color' => 'success',
                'type' => 'student',
            ];
        }

        // Recent teachers (last 3)
        $recentTeachers = Staff::where('school_id', $school->id)
            ->where('staff_type', 'Teacher')
            ->with(['user', 'department'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentTeachers as $teacher) {
            $recentActivities[] = [
                'icon' => 'briefcase',
                'title' => 'New Teacher Added',
                'description' => $teacher->user->name . ' joined as ' . ($teacher->department->name ?? 'teaching') . ' staff',
                'time' => $teacher->created_at->diffForHumans(),
                'color' => 'info',
                'type' => 'teacher',
            ];
        }

        // Recent announcements
        foreach ($announcements->take(3) as $announcement) {
            $recentActivities[] = [
                'icon' => 'megaphone',
                'title' => 'Announcement Published',
                'description' => $announcement->title,
                'time' => $announcement->announced_at->diffForHumans(),
                'color' => 'primary',
                'type' => 'announcement',
            ];
        }

        // Sort all activities by time
        usort($recentActivities, function($a, $b) {
            return strtotime($a['time']) <=> strtotime($b['time']);
        });

        // Take top 10 recent activities
        $recentActivities = array_slice($recentActivities, 0, 10);

        // Academic quick stats
        $quickStats = [
            'pending_admissions' => \App\Models\AdmissionApplication::where('school_id', $school->id)->where('status', 'pending')->count(),
            'upcoming_events' => \App\Models\Event::where('school_id', $school->id)->where('event_date', '>=', now())->count(),
            'active_teachers' => Staff::where('school_id', $school->id)
                ->where('staff_type', 'Teacher')
                ->whereHas('user', function($q) {
                    $q->where('last_login', '>=', now()->startOfDay());
                })->count(),
        ];

        // Student Documents for principal (view only, their school)
        $documentStats = [
            'total_documents' => StudentDocument::where('school_id', $school->id)->count(),
            'missing_documents' => Student::where('school_id', $school->id)
                ->where('status', 'active')
                ->whereDoesntHave('documents', function($q) {
                    $q->where('document_type', StudentDocument::TYPE_BIRTH_CERTIFICATE);
                })
                ->count(),
            'expiring_soon' => StudentDocument::where('school_id', $school->id)
                ->where('status', 'active')
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<=', now()->addDays(30))
                ->where('expiry_date', '>', now())
                ->count(),
        ];

        // Recent documents (last 8)
        $recentDocuments = StudentDocument::where('school_id', $school->id)
            ->with(['student.user', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('dashboard.principal', compact(
            'user',
            'school',
            'stats',
            'currentSession',
            'currentTerm',
            'attendanceData',
            'attendanceLabels',
            'genderStats',
            'classesWithArms',
            'notifications',
            'announcements',
            'recentActivities',
            'quickStats',
            'documentStats',
            'recentDocuments'
        ));
    }

    /**
     * Guardian Dashboard - Only their children's data
     */
    private function guardianDashboard($user, $school)
    {
        // Guardian-specific dashboard logic
        return view('dashboard.guardian', compact('user', 'school'));
    }

    /**
     * Student Dashboard - Personal data only
     */
    private function studentDashboard($user, $school)
    {
        // Student-specific dashboard logic
        return view('dashboard.student', compact('user', 'school'));
    }
}
