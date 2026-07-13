<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContinuousAssessmentQuestion extends Model {
    use SoftDeletes;

    protected $fillable = [
        'assessment_id', 'title', 'question', 'question_type',
        'marks', 'difficulty', 'image', 'status'
    ];

    protected $casts = [
        'marks' => 'decimal:2'
    ];

    public function assessment() {
        return $this->belongsTo(ContinuousAssessment::class, 'assessment_id');
    }

    public function options() {
        return $this->hasMany(ContinuousAssessmentQuestionOption::class, 'question_id');
    }
}