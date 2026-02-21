<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseActivity extends Model {
    use HasFactory;

    protected $fillable = ['warehouse_id', 'warehouse_order_id', 'user_id', 'type', 'description', 'product_id', 'quantity', 'warehouse_location_id'];

    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function order() { return $this->belongsTo(WarehouseOrder::class, 'warehouse_order_id'); }
    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function location() { return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id'); }
}
