<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model {
    protected $fillable = [
        'school_id', 'category_id', 'item_name', 'serial_number',
        'quantity', 'unit_price', 'condition', 'location', 'status'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2'
    ];

    public function category() {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }
}