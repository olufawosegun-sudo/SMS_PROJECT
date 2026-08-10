<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = ['school_id', 'class_id', 'fee_category_id', 'amount', 'session_id', 'term_id'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function feeCategory()
    {
        return $this->belongsTo(FeeCategory::class);
    }
}
