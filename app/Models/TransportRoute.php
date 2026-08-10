<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportRoute extends Model
{
    protected $fillable = ['school_id', 'name', 'pickup_point', 'destination', 'fee', 'status'];

    protected $casts = [
        'fee' => 'decimal:2',
    ];
}
