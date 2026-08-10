<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'uuid', 'school_id', 'school_branch_id', 'invoice_id', 'student_id', 'reference', 'payment_reference',
        'payment_method', 'gateway', 'amount', 'currency', 'received_by',
        'status', 'paid_at',
    ];

    public function getReferenceAttribute()
    {
        return $this->attributes['payment_reference'] ?? $this->attributes['reference'] ?? null;
    }

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function schoolBranch()
    {
        return $this->belongsTo(SchoolBranch::class, 'school_branch_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
