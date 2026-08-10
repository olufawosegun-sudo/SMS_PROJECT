<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'uuid', 'school_id', 'school_branch_id', 'role_id', 'first_name', 'last_name', 'other_name',
        'email', 'email_verified_at', 'phone', 'gender', 'dob',
        'profile_photo', 'password', 'status', 'is_super_admin', 'last_login',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'is_super_admin' => 'boolean',
            'last_login' => 'datetime',
        ];
    }

    public function getNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin || optional($this->role)->name === 'Super Admin' || $this->email === 'superadmin@sms.com';
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolBranch()
    {
        return $this->belongsTo(SchoolBranch::class, 'school_branch_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function principal()
    {
        return $this->hasOne(Principal::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function guardian()
    {
        return $this->hasOne(Guardian::class);
    }
}
