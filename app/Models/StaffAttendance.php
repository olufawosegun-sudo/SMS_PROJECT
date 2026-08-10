<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    protected $table = 'staff_attendance';

    protected $fillable = [
        'school_id',
        'staff_id',
        'attendance_date',
        'check_in',
        'check_out',
        'status',
        'remark',
        'late_minutes',
        'early_departure_minutes',
        'recorded_by',
        'approved_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'late_minutes' => 'integer',
        'early_departure_minutes' => 'integer',
    ];

    /**
     * Get the staff member for this attendance record.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Get the school for this attendance record.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user who recorded this attendance.
     */
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Get the user who approved this attendance (for special cases).
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if staff was late.
     */
    public function isLate(): bool
    {
        return $this->status === 'late' || $this->late_minutes > 0;
    }

    /**
     * Check if staff departed early.
     */
    public function departedEarly(): bool
    {
        return $this->early_departure_minutes > 0;
    }

    /**
     * Check if attendance was approved (for special cases).
     */
    public function isApproved(): bool
    {
        return ! is_null($this->approved_by);
    }
}
