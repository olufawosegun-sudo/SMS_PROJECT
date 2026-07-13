<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowedBook extends Model {
    protected $fillable = [
        'book_id', 'student_id', 'borrow_date', 'due_date',
        'return_date', 'fine_amount', 'status'
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine_amount' => 'decimal:2'
    ];

    public function book() {
        return $this->belongsTo(LibraryBook::class, 'book_id');
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}