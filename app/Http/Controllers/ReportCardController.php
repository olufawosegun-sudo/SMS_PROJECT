<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportCardController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::user()->school;
        $classes = SchoolClass::where('school_id', $school->id)->orderBy('name')->get();
        $selectedClassId = $request->get('class_id');

        $studentsQuery = Student::where('school_id', $school->id)
            ->where('status', 'active')
            ->with(['user', 'schoolClass']);

        if ($selectedClassId) {
            $studentsQuery->where('class_id', $selectedClassId);
        }

        $students = $studentsQuery->orderBy('class_id')->get();

        // Get results summary per student
        $studentResults = [];
        foreach ($students as $student) {
            $results = Result::where('school_id', $school->id)
                ->where('student_id', $student->id)
                ->with('subject')
                ->get();

            $studentResults[$student->id] = [
                'total_subjects' => $results->count(),
                'average' => $results->count() > 0 ? round($results->avg('total_score'), 1) : 0,
                'highest' => $results->max('total_score') ?? 0,
                'lowest' => $results->min('total_score') ?? 0,
                'results' => $results,
            ];
        }

        return view('report-cards.index', compact('classes', 'students', 'studentResults', 'selectedClassId', 'school'));
    }
}
