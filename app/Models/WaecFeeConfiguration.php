<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaecFeeConfiguration extends Model
{
    protected $fillable = [
        'school_id',
        'session_id',
        'fee_type',
        'fee_name',
        'amount',
        'currency',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the school that owns this fee configuration.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the academic session for this fee configuration.
     */
    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    /**
     * Get the user who created this configuration.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this configuration.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include active fees.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to filter by school.
     */
    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Scope a query to filter by session.
     */
    public function scopeForSession($query, int $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Get fees for a specific session.
     */
    public static function getSessionFees(int $schoolId, int $sessionId)
    {
        return static::forSchool($schoolId)
            ->forSession($sessionId)
            ->active()
            ->get();
    }

    /**
     * Get total fee amount for a session.
     */
    public static function getTotalSessionFee(int $schoolId, int $sessionId)
    {
        return static::forSchool($schoolId)
            ->forSession($sessionId)
            ->active()
            ->sum('amount');
    }
}
