<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {
    protected $fillable = [
        'uuid', 'school_id', 'student_id', 'class_id', 'session_id', 'term_id',
        'invoice_number', 'currency', 'total_amount', 'paid_amount', 'balance', 'status',
        'due_date', 'issued_by'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'due_date' => 'date'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function items() {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }
}