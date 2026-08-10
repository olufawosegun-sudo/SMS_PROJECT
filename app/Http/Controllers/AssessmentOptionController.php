<?php

namespace App\Http\Controllers;

use App\Models\ContinuousAssessmentQuestion;
use App\Models\ContinuousAssessmentQuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentOptionController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::user()->school;

        // Get all questions for dropdown filter
        $questions = ContinuousAssessmentQuestion::whereHas('assessment', function ($query) use ($school) {
            $query->where('school_id', $school->id);
        })->with(['assessment.schoolClass', 'assessment.subject'])->get();

        // Filter by question if selected
        $selectedQuestionId = $request->get('question_id');

        $optionsQuery = ContinuousAssessmentQuestionOption::whereHas('question.assessment', function ($query) use ($school) {
            $query->where('school_id', $school->id);
        })->with(['question.assessment.schoolClass', 'question.assessment.subject']);

        if ($selectedQuestionId) {
            $optionsQuery->where('question_id', $selectedQuestionId);
        }

        $options = $optionsQuery->orderBy('created_at', 'desc')->get();

        return view('assessment-options.index', compact('questions', 'options', 'selectedQuestionId', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:continuous_assessment_questions,id',
            'option_label' => 'required|string|max:10',
            'option_text' => 'required|string',
            'is_correct' => 'required|boolean',
        ]);

        ContinuousAssessmentQuestionOption::create([
            'question_id' => $request->question_id,
            'option_label' => $request->option_label,
            'option_text' => $request->option_text,
            'is_correct' => $request->is_correct,
        ]);

        return redirect()->back()->with('success', 'Option added successfully!');
    }

    public function destroy($id)
    {
        ContinuousAssessmentQuestionOption::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Option deleted successfully!');
    }
}
