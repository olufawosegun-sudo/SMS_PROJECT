<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model {
    protected $fillable = [
        'school_id', 'school_branch_id', 'title', 'content', 'body', 'target', 'audience', 'priority', 'published_by',
        'announced_at', 'published_at', 'expires_at', 'status'
    ];

    protected $casts = [
        'announced_at' => 'datetime',
        'published_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public function schoolBranch() {
        return $this->belongsTo(SchoolBranch::class, 'school_branch_id');
    }
}