<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model {
    protected $table = 'teacher_attendance';

    protected $fillable = ['school_id', 'teacher_id', 'attendance_date', 'check_in', 'check_out', 'status', 'remark'];

    protected $casts = [
        'attendance_date' => 'date'
    ];
}