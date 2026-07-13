<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model {
    protected $fillable = [
        'teacher_id', 'subject_id', 'class_id', 'session_id',
        'term_id', 'week', 'topic', 'objectives', 'content', 'status'
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

    public function notes() {
        return $this->hasMany(LessonNote::class, 'lesson_plan_id');
    }
}