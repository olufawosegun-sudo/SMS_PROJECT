<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model {
    protected $table = 'student_attendance';

    protected $fillable = [
        'school_id', 'student_id', 'class_id', 'session_id',
        'term_id', 'attendance_date', 'attendance_time', 'status', 'remark', 'recorded_by'
    ];

    protected $casts = [
        'attendance_date' => 'date'
    ];
}