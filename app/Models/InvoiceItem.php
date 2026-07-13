<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model {
    protected $fillable = ['invoice_id', 'fee_category_id', 'amount'];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function feeCategory() {
        return $this->belongsTo(FeeCategory::class);
    }
}