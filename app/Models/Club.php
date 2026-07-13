<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model {
    protected $fillable = ['school_id', 'name', 'description', 'patron_id', 'status'];

    public function members() {
        return $this->belongsToMany(Student::class, 'club_members', 'club_id', 'student_id');
    }
}