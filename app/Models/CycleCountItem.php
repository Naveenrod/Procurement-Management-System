<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CycleCountItem extends Model {
    use HasFactory;

    protected $fillable = ['cycle_count_id', 'product_id', 'warehouse_location_id', 'system_quantity', 'counted_quantity', 'variance'];

    public function cycleCount() { return $this->belongsTo(CycleCount::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function location() { return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id'); }
}
