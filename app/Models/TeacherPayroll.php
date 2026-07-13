<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherPayroll extends Model {
    protected $table = 'teacher_payroll';

    protected $fillable = [
        'teacher_id', 'month', 'year', 'basic_salary', 'allowance',
        'deduction', 'net_salary', 'payment_status', 'paid_at'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowance' => 'decimal:2',
        'deduction' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}