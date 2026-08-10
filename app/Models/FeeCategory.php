<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeCategory extends Model
{
    protected $fillable = ['school_id', 'name', 'description', 'amount', 'status'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
