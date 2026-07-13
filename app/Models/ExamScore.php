<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamScore extends Model {
    protected $fillable = ['school_id', 'student_id', 'subject_id', 'exam_id', 'score', 'maximum_score'];

    protected $casts = [
        'score' => 'decimal:2',
        'maximum_score' => 'decimal:2'
    ];

    public function exam() {
        return $this->belongsTo(Examination::class, 'exam_id');
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}