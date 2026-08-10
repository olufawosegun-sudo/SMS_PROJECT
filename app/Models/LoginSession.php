<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginSession extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'device', 'browser',
        'operating_system', 'ip_address', 'login_at', 'logout_at', 'status',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];
}
