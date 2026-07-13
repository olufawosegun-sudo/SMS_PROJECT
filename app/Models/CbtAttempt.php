<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtAttempt extends Model {
    protected $fillable = ['cbt_exam_id', 'student_id', 'score', 'started_at', 'submitted_at', 'status'];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'score' => 'decimal:2'
    ];

    public function cbtExam() {
        return $this->belongsTo(CbtExam::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}