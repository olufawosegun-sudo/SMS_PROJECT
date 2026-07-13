<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsQueue extends Model {
    protected $table = 'sms_queue';

    protected $fillable = ['school_id', 'recipient', 'message', 'status', 'scheduled_at', 'sent_at'];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime'
    ];
}