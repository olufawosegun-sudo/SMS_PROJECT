<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $table = 'sms_logs';

    protected $fillable = ['school_id', 'recipient', 'message', 'status', 'gateway_response', 'sent_at'];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
