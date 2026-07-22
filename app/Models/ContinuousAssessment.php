<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContinuousAssessment extends Model {
    protected $fillable = [
        'school_id', 'session_id', 'term_id', 'class_id',
        'subject_id', 'staff_id', 'title', 'description', 'total_marks',
        'weight', 'start_time', 'end_time', 'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function getMaxScoreAttribute()
    {
        return $this->total_marks;
    }

    public function setMaxScoreAttribute($value)
    {
        $this->attributes['total_marks'] = $value;
    }

    public function staff() {
        return $this->belongsTo(Staff::class);
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