<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'school_id',
        'user_id',
        'department_id',
        'staff_no',
        'staff_type',
        'qualification',
        'specialization',
        'employment_date',
        'confirmation_date',
        'years_of_experience',
        'previous_employer',
        'employment_type',
        'contract_type',
        'contract_start_date',
        'contract_end_date',
        'salary',
        'payment_frequency',
        'bank_name',
        'account_number',
        'account_name',
        'office_location',
        'job_description',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'appointment_letter',
        'resume_cv',
        'certificates',
        'status',
        'resignation_date',
        'termination_date',
        'exit_notes',
    ];

    protected $casts = [
        'employment_date' => 'date',
        'confirmation_date' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'resignation_date' => 'date',
        'termination_date' => 'date',
        'salary' => 'decimal:2',
        'years_of_experience' => 'integer',
        'certificates' => 'array',
    ];

    /**
     * Get the school that owns the staff member.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user that owns the staff member.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department that the staff member belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the subjects taught by this staff member (if they teach).
     */
    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class, 'staff_id');
    }

    /**
     * Get the full name of the staff member.
     */
    public function getFullNameAttribute()
    {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }

    /**
     * Check if staff member is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if staff member is a teacher.
     */
    public function isTeacher()
    {
        return $this->staff_type === 'Teacher';
    }

    /**
     * Check if staff member is a principal.
     */
    public function isPrincipal()
    {
        return in_array($this->staff_type, ['Principal', 'Vice Principal', 'Assistant Principal']);
    }

    /**
     * Scope: Get active staff.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Get staff by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('staff_type', $type);
    }

    /**
     * Scope: Get teaching staff (who have subject assignments).
     */
    public function scopeTeachingStaff($query)
    {
        return $query->whereHas('teacherSubjects');
    }

    /**
     * Scope: Get staff by school.
     */
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }
}
