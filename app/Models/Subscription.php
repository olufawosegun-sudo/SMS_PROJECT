<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model {
    protected $fillable = [
        'school_id', 'plan', 'price', 'billing_cycle', 'starts_at',
        'ends_at', 'status', 'payment_reference'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime'
    ];

    public function school() {
        return $this->belongsTo(School::class);
    }

    public function payments() {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function isActive(): bool {
        return $this->status === 'active' && ($this->ends_at === null || $this->ends_at->isFuture());
    }

    public function isExpired(): bool {
        return $this->status === 'expired' || ($this->ends_at && $this->ends_at->isPast());
    }

    public function isExpiringSoon(int $days = 14): bool {
        if (!$this->ends_at || !$this->isActive()) return false;
        return $this->ends_at->diffInDays(now()) <= $days;
    }

    public function daysRemaining(): int {
        if (!$this->ends_at || $this->ends_at->isPast()) return 0;
        return (int) now()->diffInDays($this->ends_at);
    }
}