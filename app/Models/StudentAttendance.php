<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    protected $table = 'student_attendance';

    protected $fillable = [
        'school_id',
        'student_id',
        'class_id',
        'session_id',
        'term_id',
        'attendance_date',
        'attendance_time',
        'status',
        'remark',
        'recorded_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'attendance_time' => 'datetime',
    ];

    /**
     * Get the student for this attendance record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the school for this attendance record.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the class for this attendance record.
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the academic session.
     */
    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    /**
     * Get the academic term.
     */
    public function term()
    {
        return $this->belongsTo(AcademicTerm::class, 'term_id');
    }

    /**
     * Get the user who recorded this attendance.
     */
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Check if student was present.
     */
    public function isPresent(): bool
    {
        return $this->status === 'present';
    }

    /**
     * Check if student was absent.
     */
    public function isAbsent(): bool
    {
        return $this->status === 'absent';
    }

    /**
     * Check if student was late.
     */
    public function isLate(): bool
    {
        return $this->status === 'late';
    }

    /**
     * Check if student was excused.
     */
    public function isExcused(): bool
    {
        return in_array($this->status, ['sick', 'excused']);
    }
}
