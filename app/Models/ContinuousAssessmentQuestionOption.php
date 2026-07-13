<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContinuousAssessmentQuestionOption extends Model {
    protected $table = 'continuous_assessment_question_options';

    protected $fillable = ['question_id', 'option_label', 'option_text', 'image', 'is_correct'];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function question() {
        return $this->belongsTo(ContinuousAssessmentQuestion::class, 'question_id');
    }
}