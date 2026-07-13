<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model {
    protected $fillable = [
        'school_id', 'expense_category_id', 'title', 'amount',
        'paid_to', 'expense_date', 'description', 'approved_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date'
    ];

    public function category() {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
}