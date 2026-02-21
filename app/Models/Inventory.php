<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model {
    use HasFactory;

    protected $table = 'inventory';
    protected $fillable = ['warehouse_id', 'product_id', 'warehouse_location_id', 'quantity_on_hand', 'quantity_reserved'];

    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function location() { return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id'); }
    public function transactions() { return $this->hasMany(InventoryTransaction::class); }

    public function getQuantityAvailableAttribute(): float {
        return max(0, $this->quantity_on_hand - $this->quantity_reserved);
    }
}
