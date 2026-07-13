<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model {
    protected $fillable = [
        'school_id', 'student_id', 'class_id', 'session_id', 'term_id',
        'average', 'overall_position', 'attendance', 'principal_comment',
        'teacher_comment', 'generated_by', 'generated_at', 'status'
    ];

    protected $casts = [
        'average' => 'decimal:2',
        'generated_at' => 'datetime'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }
}