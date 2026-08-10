<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolBranch extends Model
{
    protected $fillable = [
        'school_id', 'name', 'address', 'address_line_1', 'address_line_2', 'address_line_3', 'city', 'state',
        'country', 'phone', 'email', 'status',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'school_branch_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'school_branch_id');
    }

    public function staff()
    {
        return $this->hasMany(Staff::class, 'school_branch_id');
    }

    public function schoolClasses()
    {
        return $this->hasMany(SchoolClass::class, 'school_branch_id');
    }

    public function classArms()
    {
        return $this->hasMany(ClassArm::class, 'school_branch_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'school_branch_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'school_branch_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'school_branch_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'school_branch_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'school_branch_id');
    }
}
