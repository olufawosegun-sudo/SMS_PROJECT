<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Principal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'user_id',
        'department_id',
        'staff_no',
        'qualification',
        'specialization',
        'employment_date',
        'years_of_experience',
        'previous_school',
        'office_location',
        'emergency_contact',
        'emergency_contact_relationship',
        'contract_type',
        'appointment_letter',
        'salary',
        'contract_start_date',
        'contract_end_date',
        'principal_type',
        'status',
    ];

    protected $casts = [
        'employment_date' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'salary' => 'decimal:2',
        'years_of_experience' => 'integer',
    ];

    /**
     * Get the school that owns the principal.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user that owns the principal.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department that the principal belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the full name of the principal.
     */
    public function getFullNameAttribute()
    {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }

    /**
     * Check if principal is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if principal is on leave.
     */
    public function isOnLeave()
    {
        return $this->status === 'on_leave';
    }

    /**
     * Scope: Get active principals.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Get principals by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('principal_type', $type);
    }
}
