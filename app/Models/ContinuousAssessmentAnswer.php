<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContinuousAssessmentAnswer extends Model
{
    protected $table = 'continuous_assessment_answers';

    protected $fillable = [
        'question_id', 'student_id', 'selected_option_id',
        'answer_text', 'score', 'is_correct', 'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'is_correct' => 'boolean',
        'score' => 'decimal:2',
    ];

    public function question()
    {
        return $this->belongsTo(ContinuousAssessmentQuestion::class, 'question_id');
    }

    public function selectedOption()
    {
        return $this->belongsTo(ContinuousAssessmentQuestionOption::class, 'selected_option_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
