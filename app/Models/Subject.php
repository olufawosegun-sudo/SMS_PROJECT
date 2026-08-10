<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = ['school_id', 'name', 'code', 'category', 'is_core', 'status'];

    protected $casts = [
        'is_core' => 'boolean',
    ];
}
