<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaecPaymentApproval extends Model
{
    protected $fillable = [
        'payment_id',
        'user_id',
        'action',
        'previous_status',
        'new_status',
        'comment',
        'reason',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the payment.
     */
    public function payment()
    {
        return $this->belongsTo(WaecPayment::class, 'payment_id');
    }

    /**
     * Get the user who performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to filter by payment.
     */
    public function scopeForPayment($query, int $paymentId)
    {
        return $query->where('payment_id', $paymentId);
    }

    /**
     * Scope a query to filter by action.
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Create audit record for payment action.
     */
    public static function logAction(
        int $paymentId,
        int $userId,
        string $action,
        ?string $previousStatus,
        string $newStatus,
        ?string $comment = null,
        ?string $reason = null
    ) {
        return static::create([
            'payment_id' => $paymentId,
            'user_id' => $userId,
            'action' => $action,
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'comment' => $comment,
            'reason' => $reason,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
