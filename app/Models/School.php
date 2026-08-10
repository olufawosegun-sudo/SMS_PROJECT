<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid', 'name', 'school_code', 'email', 'phone', 'website',
        'address', 'address_line_1', 'address_line_2', 'address_line_3', 'city', 'state', 'country', 'logo', 'motto',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function branches()
    {
        return $this->hasMany(SchoolBranch::class);
    }

    public function guardians()
    {
        return $this->hasMany(Guardian::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function sessions()
    {
        return $this->hasMany(AcademicSession::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }
}
