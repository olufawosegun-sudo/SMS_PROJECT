<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubMember extends Model {
    protected $fillable = ['club_id', 'student_id', 'staff_id', 'joined_at', 'status'];

    protected $casts = [
        'joined_at' => 'datetime'
    ];
}