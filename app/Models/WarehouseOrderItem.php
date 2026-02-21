<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseOrderItem extends Model {
    use HasFactory;

    protected $fillable = ['warehouse_order_id', 'product_id', 'expected_quantity', 'received_quantity', 'picked_quantity', 'warehouse_location_id', 'status'];

    public function warehouseOrder() { return $this->belongsTo(WarehouseOrder::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function location() { return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id'); }
}
