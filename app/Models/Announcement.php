<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model {
    protected $fillable = [
        'school_id', 'title', 'body', 'target', 'published_by',
        'announced_at', 'expires_at', 'status'
    ];

    protected $casts = [
        'announced_at' => 'datetime',
        'expires_at' => 'datetime'
    ];
}