<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'payroll';

    protected $fillable = [
        'school_id', 'staff_id', 'month', 'year', 'basic_salary', 'allowance',
        'deduction', 'net_salary', 'payment_status', 'paid_at',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowance' => 'decimal:2',
        'deduction' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
