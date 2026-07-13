<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model {
    protected $fillable = [
        'school_id', 'student_id', 'class_id', 'subject_id', 'session_id',
        'term_id', 'exam_id', 'ca_score', 'exam_score', 'total', 'grade',
        'remark', 'position', 'published_at'
    ];

    protected $casts = [
        'ca_score' => 'decimal:2',
        'exam_score' => 'decimal:2',
        'total' => 'decimal:2',
        'published_at' => 'datetime'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function term() {
        return $this->belongsTo(AcademicTerm::class, 'term_id');
    }

    public function approvals() {
        return $this->hasMany(ResultApproval::class);
    }
}