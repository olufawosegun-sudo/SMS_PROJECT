<?php

namespace App\Http\Controllers;

use App\Models\ContinuousAssessment;
use App\Models\ContinuousAssessmentQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentQuestionController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::user()->school;
        
        // Get all continuous assessments for dropdown filter
        $assessments = ContinuousAssessment::where('school_id', $school->id)
            ->with(['schoolClass', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Filter by assessment if selected
        $selectedAssessmentId = $request->get('assessment_id');
        
        $questionsQuery = ContinuousAssessmentQuestion::whereHas('assessment', function($query) use ($school) {
            $query->where('school_id', $school->id);
        })->with(['assessment.schoolClass', 'assessment.subject']);
        
        if ($selectedAssessmentId) {
            $questionsQuery->where('assessment_id', $selectedAssessmentId);
        }
        
        $questions = $questionsQuery->orderBy('created_at', 'desc')->get();
        
        return view('assessment-questions.index', compact('assessments', 'questions', 'selectedAssessmentId', 'school'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:continuous_assessments,id',
            'question' => 'required|string',
            'question_type' => 'required|in:Multiple Choice,True/False,Short Answer,Essay',
            'marks' => 'required|numeric|min:0',
            'difficulty' => 'nullable|in:Easy,Medium,Hard',
        ]);
        
        ContinuousAssessmentQuestion::create([
            'assessment_id' => $request->assessment_id,
            'question' => $request->question,
            'question_type' => $request->question_type,
            'marks' => $request->marks,
            'difficulty' => $request->difficulty ?? 'Medium',
            'status' => 'active',
        ]);
        
        return redirect()->back()->with('success', 'Question added successfully!');
    }
    
    public function destroy($id)
    {
        ContinuousAssessmentQuestion::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Question deleted successfully!');
    }
}
