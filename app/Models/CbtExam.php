<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtExam extends Model {
    protected $table = 'cbt_exams';

    protected $fillable = [
        'school_id', 'session_id', 'term_id', 'class_id',
        'subject_id', 'title', 'duration', 'total_marks', 'start_time', 'end_time', 'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}