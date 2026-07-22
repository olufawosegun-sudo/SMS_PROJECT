<?php

namespace App\Http\Controllers;

use App\Models\ContinuousAssessmentAnswer;
use App\Models\ContinuousAssessmentQuestion;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentAnswerController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::user()->school;
        
        // Get all questions for dropdown filter
        $questions = ContinuousAssessmentQuestion::whereHas('assessment', function($query) use ($school) {
            $query->where('school_id', $school->id);
        })->with(['assessment.schoolClass', 'assessment.subject'])->get();
        
        // Get all students for filter
        $students = Student::where('school_id', $school->id)
            ->where('status', 'active')
            ->with('user')
            ->get();
        
        // Filter by question and/or student
        $selectedQuestionId = $request->get('question_id');
        $selectedStudentId = $request->get('student_id');
        
        $answersQuery = ContinuousAssessmentAnswer::whereHas('question.assessment', function($query) use ($school) {
            $query->where('school_id', $school->id);
        })->with(['question.assessment', 'student.user']);
        
        if ($selectedQuestionId) {
            $answersQuery->where('question_id', $selectedQuestionId);
        }
        
        if ($selectedStudentId) {
            $answersQuery->where('student_id', $selectedStudentId);
        }
        
        $answers = $answersQuery->orderBy('created_at', 'desc')->get();
        
        return view('assessment-answers.index', compact('questions', 'students', 'answers', 'selectedQuestionId', 'selectedStudentId', 'school'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:continuous_assessment_questions,id',
            'student_id' => 'required|exists:students,id',
            'answer_text' => 'nullable|string',
            'selected_option_id' => 'nullable|exists:continuous_assessment_question_options,id',
            'score' => 'nullable|numeric|min:0',
            'is_correct' => 'nullable|boolean',
        ]);
        
        ContinuousAssessmentAnswer::create([
            'question_id' => $request->question_id,
            'student_id' => $request->student_id,
            'answer_text' => $request->answer_text,
            'selected_option_id' => $request->selected_option_id,
            'score' => $request->score,
            'is_correct' => $request->is_correct,
            'submitted_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Answer recorded successfully!');
    }
    
    public function destroy($id)
    {
        ContinuousAssessmentAnswer::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Answer deleted successfully!');
    }
}
