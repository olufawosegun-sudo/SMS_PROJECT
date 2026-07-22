<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model {
    protected $fillable = [
        'school_id', 'student_id', 'class_id', 'subject_id', 'session_id',
        'term_id', 'exam_id', 'ca_score', 'exam_score', 'total_score', 'grade',
        'remark', 'position', 'published_at', 'recorded_by', 'recorded_at'
    ];

    protected $casts = [
        'ca_score' => 'decimal:2',
        'exam_score' => 'decimal:2',
        'total_score' => 'decimal:2',
        'published_at' => 'datetime',
        'recorded_at' => 'datetime'
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

    public function session() {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    public function term() {
        return $this->belongsTo(AcademicTerm::class, 'term_id');
    }

    public function recordedBy() {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function approvals() {
        return $this->hasMany(ResultApproval::class);
    }

    // Helper to get teacher's initials for signature
    public function getTeacherSignature()
    {
        if ($this->recordedBy) {
            $firstName = $this->recordedBy->first_name ?? '';
            $lastName = $this->recordedBy->last_name ?? '';
            return substr($firstName, 0, 1) . substr($lastName, 0, 1);
        }
        return '--';
    }
}