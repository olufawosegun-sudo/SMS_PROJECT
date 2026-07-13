<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContinuousAssessment extends Model {
    protected $fillable = [
        'school_id', 'session_id', 'term_id', 'class_id',
        'subject_id', 'teacher_id', 'title', 'description', 'total_marks',
        'start_time', 'end_time', 'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function questions() {
        return $this->hasMany(ContinuousAssessmentQuestion::class, 'assessment_id');
    }
}