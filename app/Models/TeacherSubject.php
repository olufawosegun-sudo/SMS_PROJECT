<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model {
    protected $fillable = [
        'school_id', 'teacher_id', 'class_id', 'subject_id', 'session_id', 'term_id'
    ];

    public function school() {
        return $this->belongsTo(School::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function academicSession() {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    public function academicTerm() {
        return $this->belongsTo(AcademicTerm::class, 'term_id');
    }
}
