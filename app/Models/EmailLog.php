<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model {
    protected $table = 'email_logs';

    protected $fillable = ['school_id', 'recipient', 'subject', 'status', 'gateway_response', 'sent_at'];

    protected $casts = [
        'sent_at' => 'datetime'
    ];
}