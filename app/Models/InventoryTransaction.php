<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model {
    protected $fillable = [
        'item_id', 'issued_to', 'issued_by', 'quantity',
        'transaction_type', 'transaction_date', 'remarks'
    ];

    protected $casts = [
        'transaction_date' => 'date'
    ];

    public function item() {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }
}