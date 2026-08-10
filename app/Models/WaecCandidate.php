<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WaecCandidate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'school_id',
        'student_id',
        'session_id',
        'class_id',
        'arm_id',
        'examination_fee',
        'registration_fee',
        'other_charges',
        'total_fee',
        'amount_paid',
        'balance',
        'payment_status',
        'candidate_number',
        'status',
        'registration_date',
        'notes',
        'registered_by',
        'registered_at',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
        'waec_remittance_id',
    ];

    protected $casts = [
        'examination_fee' => 'decimal:2',
        'registration_fee' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
        'registration_date' => 'date',
        'registered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($candidate) {
            if (empty($candidate->uuid)) {
                $candidate->uuid = (string) Str::uuid();
            }
            if (empty($candidate->registered_at)) {
                $candidate->registered_at = now();
            }
            if (empty($candidate->registration_date)) {
                $candidate->registration_date = now()->toDateString();
            }
        });

        static::saving(function ($candidate) {
            // Auto-calculate balance
            $candidate->balance = $candidate->total_fee - $candidate->amount_paid;

            // Auto-update payment status
            if ($candidate->amount_paid >= $candidate->total_fee && $candidate->total_fee > 0) {
                $candidate->payment_status = 'paid';
            } elseif ($candidate->amount_paid > 0) {
                $candidate->payment_status = 'partial';
            } else {
                $candidate->payment_status = 'unpaid';
            }
        });
    }

    /**
     * Get the school that owns this candidate.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the student.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the academic session.
     */
    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    /**
     * Get the class.
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the class arm.
     */
    public function arm()
    {
        return $this->belongsTo(ClassArm::class, 'arm_id');
    }

    /**
     * Get the user who registered this candidate.
     */
    public function registrar()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    /**
     * Get the user who cancelled this candidate.
     */
    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Get the WAEC remittance batch.
     */
    public function remittance()
    {
        return $this->belongsTo(WaecRemittance::class, 'waec_remittance_id');
    }

    /**
     * Get all payments for this candidate.
     */
    public function payments()
    {
        return $this->hasMany(WaecPayment::class, 'candidate_id');
    }

    /**
     * Get approved payments only.
     */
    public function approvedPayments()
    {
        return $this->hasMany(WaecPayment::class, 'candidate_id')
            ->where('status', 'approved');
    }

    /**
     * Scope a query to only include active candidates.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['registered', 'payment_pending', 'payment_complete', 'exam_ready']);
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
     * Scope a query to filter by payment status.
     */
    public function scopePaymentStatus($query, string $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Check if candidate has paid in full.
     */
    public function isFullyPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if candidate is active.
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['registered', 'payment_pending', 'payment_complete', 'exam_ready']);
    }

    /**
     * Update amount paid and recalculate balance.
     */
    public function updatePaymentStatus()
    {
        $totalPaid = $this->approvedPayments()->sum('amount');
        $this->update([
            'amount_paid' => $totalPaid,
            'balance' => $this->total_fee - $totalPaid,
        ]);
    }
}
