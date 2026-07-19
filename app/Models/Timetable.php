<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model {
    protected $fillable = [
        'school_id', 'class_id', 'subject_id', 'teacher_id',
        'session_id', 'term_id', 'classroom_room_id', 'day', 'start_time', 'end_time'
    ];

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function teacher() {
        return $this->belongsTo(Staff::class, 'teacher_id');
    }
}