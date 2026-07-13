<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}