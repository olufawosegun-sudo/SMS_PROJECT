<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentHouse extends Model {
    protected $fillable = ['student_id', 'house_id', 'joined_at'];

    protected $casts = [
        'joined_at' => 'datetime'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }
}