<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\ReportCard;
use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportCardController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::user()->school;
        $userRole = Auth::user()->role->name;
        
        // DEBUG: Log what we're getting
        \Log::info('ReportCard Index Debug', [
            'userRole' => $userRole,
            'selectedStatus' => $request->get('status'),
            'school_id' => $school->id,
        ]);
        
        // Get current session and term
        $currentSession = AcademicSession::where('school_id', $school->id)
            ->where('is_current', true)
            ->first() ?? AcademicSession::where('school_id', $school->id)->first();
        
        $currentTerm = AcademicTerm::where('school_id', $school->id)
            ->where('is_current', true)
            ->first() ?? AcademicTerm::where('school_id', $school->id)->first();

        $sessions = AcademicSession::where('school_id', $school->id)->orderBy('name', 'desc')->get();
        $terms = AcademicTerm::where('school_id', $school->id)->orderBy('name')->get();
        
        // Show all classes in the school for all users
        $classes = SchoolClass::where('school_id', $school->id)->orderBy('name')->get();

        $selectedClassId = $request->get('class_id');
        $selectedSessionId = $request->get('session_id', $currentSession->id ?? null);
        $selectedTermId = $request->get('term_id', $currentTerm->id ?? null);
        $selectedStatus = $request->get('status'); // New: filter by status

        // Get report cards
        $reportCardsQuery = ReportCard::where('school_id', $school->id)
            ->with(['student.user', 'schoolClass', 'session', 'term', 'generatedBy']);

        // For Principal/Owner: Only filter by status, ignore class/session/term
        if (in_array($userRole, ['Principal', 'Owner'])) {
            if ($selectedStatus) {
                $reportCardsQuery->where('status', $selectedStatus);
            }
            // Principal/Owner sees ALL reports across all classes/sessions/terms
        } else {
            // For Teachers: Apply all filters
            if ($selectedClassId) {
                $reportCardsQuery->where('class_id', $selectedClassId);
            }
            if ($selectedSessionId) {
                $reportCardsQuery->where('session_id', $selectedSessionId);
            }
            if ($selectedTermId) {
                $reportCardsQuery->where('term_id', $selectedTermId);
            }
        }

        $reportCards = $reportCardsQuery->orderBy('created_at', 'desc')->get();
        
        // DEBUG: Log query results
        \Log::info('ReportCard Query Results', [
            'total_found' => $reportCards->count(),
            'query_conditions' => [
                'school_id' => $school->id,
                'status_filter' => $selectedStatus,
                'is_principal_owner' => in_array($userRole, ['Principal', 'Owner']),
            ],
        ]);

        // Count drafts and approved reports for Principal's attention
        $draftCount = ReportCard::where('school_id', $school->id)
            ->where('status', 'draft')
            ->count();
        
        $approvedCount = ReportCard::where('school_id', $school->id)
            ->where('status', 'approved')
            ->count();
        
        $publishedCount = ReportCard::where('school_id', $school->id)
            ->where('status', 'published')
            ->count();

        // Get students without report cards for selected filters
        $studentsWithoutReports = collect(); // Initialize as empty collection
        
        if ($selectedClassId) {
            // Get all students in the selected class
            $studentsQuery = Student::where('school_id', $school->id)
                ->where('status', 'active')
                ->where('class_id', $selectedClassId)
                ->with(['user', 'schoolClass']);

            $allStudents = $studentsQuery->get();
            
            // Get student IDs that already have reports for selected session and term
            $studentsWithReportsQuery = ReportCard::where('school_id', $school->id)
                ->where('class_id', $selectedClassId);
            
            if ($selectedSessionId) {
                $studentsWithReportsQuery->where('session_id', $selectedSessionId);
            }
            if ($selectedTermId) {
                $studentsWithReportsQuery->where('term_id', $selectedTermId);
            }
            
            $studentsWithReports = $studentsWithReportsQuery->pluck('student_id')->toArray();
            
            // Filter students who don't have reports yet
            $studentsWithoutReports = $allStudents->filter(function($student) use ($studentsWithReports) {
                return !in_array($student->id, $studentsWithReports);
            });
        }

        return view('report-cards.index', compact(
            'classes', 'sessions', 'terms', 'reportCards', 'studentsWithoutReports',
            'selectedClassId', 'selectedSessionId', 'selectedTermId', 'selectedStatus',
            'currentSession', 'currentTerm', 'school', 'userRole', 'draftCount', 'approvedCount', 'publishedCount'
        ));
    }

    public function create(Request $request)
    {
        $school = Auth::user()->school;
        $userRole = Auth::user()->role->name;
        
        // Only Teachers can create report cards (not Principal or Owner)
        if (in_array($userRole, ['Principal', 'Owner'])) {
            return redirect()->route('report-cards.index')
                ->with('error', 'Only teachers can create report cards. Your role is to review and approve.');
        }
        
        $studentId = $request->get('student_id');
        
        if (!$studentId) {
            return redirect()->back()->with('error', 'Student ID is required');
        }

        $student = Student::where('school_id', $school->id)
            ->where('id', $studentId)
            ->with(['user', 'schoolClass'])
            ->firstOrFail();

        $sessions = AcademicSession::where('school_id', $school->id)->orderBy('name', 'desc')->get();
        $terms = AcademicTerm::where('school_id', $school->id)->orderBy('name')->get();

        $currentSession = AcademicSession::where('school_id', $school->id)
            ->where('is_current', true)
            ->first() ?? $sessions->first();
        
        $currentTerm = AcademicTerm::where('school_id', $school->id)
            ->where('is_current', true)
            ->first() ?? $terms->first();

        // Get existing results for this student in current session/term
        //Note: We fetch for current session/term by default, but the form allows changing
        $sessionId = $request->get('session_id', $currentSession->id ?? null);
        $termId = $request->get('term_id', $currentTerm->id ?? null);
        
        $existingResults = Result::where('school_id', $school->id)
            ->where('student_id', $student->id)
            ->where('session_id', $sessionId)
            ->where('term_id', $termId)
            ->with(['subject', 'recordedBy'])
            ->get();

        return view('report-cards.create', compact('student', 'sessions', 'terms', 'currentSession', 'currentTerm', 'school', 'existingResults', 'sessionId', 'termId'));
    }

    public function store(Request $request)
    {
        $userRole = Auth::user()->role->name;
        
        // Only Teachers can create report cards (not Principal or Owner)
        if (in_array($userRole, ['Principal', 'Owner'])) {
            return redirect()->route('report-cards.index')
                ->with('error', 'Only teachers can create report cards. Your role is to review and approve.');
        }
        
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'session_id' => 'required|exists:academic_sessions,id',
            'term_id' => 'required|exists:academic_terms,id',
            'teacher_comment' => 'nullable|string',
            'principal_comment' => 'nullable|string',
            'status' => 'required|in:draft,published'
        ]);

        $school = Auth::user()->school;

        // Calculate average and position
        $results = Result::where('school_id', $school->id)
            ->where('student_id', $request->student_id)
            ->where('session_id', $request->session_id)
            ->where('term_id', $request->term_id)
            ->get();

        $average = $results->count() > 0 ? round($results->avg('total_score'), 2) : 0;

        // Calculate overall position in class
        $classAverages = Result::where('school_id', $school->id)
            ->where('class_id', $request->class_id)
            ->where('session_id', $request->session_id)
            ->where('term_id', $request->term_id)
            ->groupBy('student_id')
            ->select('student_id', DB::raw('AVG(total_score) as avg_score'))
            ->orderBy('avg_score', 'desc')
            ->get();

        $position = $classAverages->search(function($item) use ($request) {
            return $item->student_id == $request->student_id;
        });
        $position = $position !== false ? $position + 1 : null;

        // Calculate attendance
        $totalSchoolDays = StudentAttendance::where('school_id', $school->id)
            ->where('class_id', $request->class_id)
            ->where('session_id', $request->session_id)
            ->where('term_id', $request->term_id)
            ->distinct('attendance_date')
            ->count('attendance_date');

        $daysPresent = StudentAttendance::where('school_id', $school->id)
            ->where('student_id', $request->student_id)
            ->where('session_id', $request->session_id)
            ->where('term_id', $request->term_id)
            ->where('status', 'present')
            ->count();

        $attendanceText = $totalSchoolDays > 0 
            ? "{$daysPresent} out of {$totalSchoolDays} days" 
            : "N/A";

        // Create or update report card
        $reportCard = ReportCard::updateOrCreate(
            [
                'school_id' => $school->id,
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'session_id' => $request->session_id,
                'term_id' => $request->term_id,
            ],
            [
                'average' => $average,
                'overall_position' => $position,
                'attendance' => $attendanceText,
                'teacher_comment' => $request->teacher_comment,
                'principal_comment' => $request->principal_comment,
                'generated_by' => Auth::id(),
                'generated_at' => now(),
                'status' => $request->status
            ]
        );

        return redirect()->route('report-cards.show', $reportCard->id)
            ->with('success', 'Report card generated successfully!');
    }

    public function show($id)
    {
        $school = Auth::user()->school;
        
        $reportCard = ReportCard::where('school_id', $school->id)
            ->where('id', $id)
            ->with(['student.user', 'schoolClass', 'session', 'term', 'generatedBy'])
            ->firstOrFail();

        // Get student results for this term
        $results = Result::where('school_id', $school->id)
            ->where('student_id', $reportCard->student_id)
            ->where('session_id', $reportCard->session_id)
            ->where('term_id', $reportCard->term_id)
            ->with(['subject', 'recordedBy'])
            ->orderBy('subject_id')
            ->get();

        // Calculate class statistics
        $classStats = [
            'total_students' => Student::where('class_id', $reportCard->class_id)->where('status', 'active')->count(),
            'class_average' => Result::where('school_id', $school->id)
                ->where('class_id', $reportCard->class_id)
                ->where('session_id', $reportCard->session_id)
                ->where('term_id', $reportCard->term_id)
                ->avg('total_score'),
            'highest_in_class' => Result::where('school_id', $school->id)
                ->where('class_id', $reportCard->class_id)
                ->where('session_id', $reportCard->session_id)
                ->where('term_id', $reportCard->term_id)
                ->max('total_score'),
            'lowest_in_class' => Result::where('school_id', $school->id)
                ->where('class_id', $reportCard->class_id)
                ->where('session_id', $reportCard->session_id)
                ->where('term_id', $reportCard->term_id)
                ->min('total_score'),
        ];

        return view('report-cards.show', compact('reportCard', 'results', 'classStats', 'school'));
    }

    public function edit($id)
    {
        $school = Auth::user()->school;
        $userRole = Auth::user()->role->name;
        
        $reportCard = ReportCard::where('school_id', $school->id)
            ->where('id', $id)
            ->with(['student.user', 'schoolClass'])
            ->firstOrFail();

        // Owner can only view (redirect to show page)
        if ($userRole === 'Owner') {
            return redirect()->route('report-cards.show', $id)
                ->with('info', 'As school owner, you can view reports but not edit them. This maintains proper accountability.');
        }

        $sessions = AcademicSession::where('school_id', $school->id)->orderBy('name', 'desc')->get();
        $terms = AcademicTerm::where('school_id', $school->id)->orderBy('name')->get();

        return view('report-cards.edit', compact('reportCard', 'sessions', 'terms', 'school'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'teacher_comment' => 'nullable|string',
            'principal_comment' => 'nullable|string',
            'status' => 'required|in:draft,approved,published'
        ]);

        $school = Auth::user()->school;
        $userRole = Auth::user()->role->name;
        
        $reportCard = ReportCard::where('school_id', $school->id)
            ->where('id', $id)
            ->firstOrFail();

        $updateData = ['status' => $request->status];
        
        // Track what action was taken
        $principalApproved = false;
        $teacherPublished = false;

        // Teachers can update teacher comment (but not Principal)
        if ($request->has('teacher_comment') && !in_array($userRole, ['Principal', 'Owner'])) {
            $updateData['teacher_comment'] = $request->teacher_comment;
        }

        // Only Principal can approve and add principal comment
        if (in_array($userRole, ['Principal', 'Owner'])) {
            if ($request->has('principal_comment')) {
                $updateData['principal_comment'] = $request->principal_comment;
            }
            
            // Principal approving (draft → approved)
            if ($reportCard->status === 'draft' && $request->status === 'approved') {
                $updateData['approved_at'] = now();
                $updateData['approved_by'] = Auth::id();
                $principalApproved = true;

                // Fetch all subject results for this student, class, session, and term
                $results = Result::where('school_id', $school->id)
                    ->where('student_id', $reportCard->student_id)
                    ->where('class_id', $reportCard->class_id)
                    ->where('session_id', $reportCard->session_id)
                    ->where('term_id', $reportCard->term_id)
                    ->get();

                // Log the approval in the result_approvals table for every subject score
                foreach ($results as $res) {
                    \App\Models\ResultApproval::updateOrCreate(
                        [
                            'result_id' => $res->id,
                        ],
                        [
                            'approved_by' => Auth::id(),
                            'status' => 'approved',
                            'approved_at' => now(),
                            'remarks' => $request->principal_comment ?? 'Approved along with report card.',
                        ]
                    );
                }
            }
        }

        // Teacher publishing approved report (approved → published)
        if (!in_array($userRole, ['Principal', 'Owner'])) {
            if ($reportCard->status === 'approved' && $request->status === 'published') {
                $updateData['published_at'] = now();
                $updateData['published_by'] = Auth::id();
                $teacherPublished = true;
            }
        }

        $reportCard->update($updateData);

        // Custom redirect based on action
        if ($principalApproved) {
            return redirect()->route('report-cards.index')
                ->with('success', '✅ Report card approved successfully! The teacher can now publish it for students and parents.');
        }

        if ($teacherPublished) {
            return redirect()->route('report-cards.index')
                ->with('success', '✅ Report card published successfully! Students and parents can now view and print it.');
        }

        return redirect()->route('report-cards.show', $reportCard->id)
            ->with('success', 'Report card updated successfully!');
    }

    public function destroy($id)
    {
        $school = Auth::user()->school;
        
        $reportCard = ReportCard::where('school_id', $school->id)
            ->where('id', $id)
            ->firstOrFail();

        $reportCard->delete();

        return redirect()->route('report-cards.index')
            ->with('success', 'Report card deleted successfully!');
    }
}
