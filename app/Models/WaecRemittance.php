<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WaecRemittance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'school_id',
        'session_id',
        'batch_reference',
        'waec_transaction_reference',
        'total_candidates_count',
        'total_amount',
        'payment_method',
        'bank_name',
        'payment_date',
        'proof_document',
        'status',
        'notes',
        'remitted_by',
        'remitted_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'total_candidates_count' => 'integer',
        'payment_date' => 'date',
        'remitted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($remittance) {
            if (empty($remittance->uuid)) {
                $remittance->uuid = (string) Str::uuid();
            }
            if (empty($remittance->remitted_at)) {
                $remittance->remitted_at = now();
            }
        });
    }

    /**
     * Get the school.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the academic session.
     */
    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    /**
     * Get user who remitted payment.
     */
    public function remitter()
    {
        return $this->belongsTo(User::class, 'remitted_by');
    }

    /**
     * Get candidates remitted in this batch.
     */
    public function candidates()
    {
        return $this->hasMany(WaecCandidate::class, 'waec_remittance_id');
    }

    /**
     * Scope for school isolation.
     */
    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }
}
