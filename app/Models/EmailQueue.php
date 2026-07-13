<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailQueue extends Model {
    protected $table = 'email_queue';

    protected $fillable = ['school_id', 'recipient', 'subject', 'message', 'status', 'scheduled_at'];

    protected $casts = [
        'scheduled_at' => 'datetime'
    ];
}