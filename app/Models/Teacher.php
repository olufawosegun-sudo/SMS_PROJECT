<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model {
    use SoftDeletes;

    protected $fillable = [
        'school_id', 'user_id', 'department_id', 'staff_no',
        'qualification', 'employment_date', 'salary', 'status'
    ];

    protected $casts = [
        'employment_date' => 'date',
        'salary' => 'decimal:2'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }
}