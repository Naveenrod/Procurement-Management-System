<?php
namespace App\Models;

use App\Enums\WarehouseOrderType;
use App\Enums\WarehouseOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseOrder extends Model {
    use HasFactory;

    protected $fillable = ['order_number', 'warehouse_id', 'type', 'status', 'purchase_order_id', 'created_by', 'notes'];
    protected $casts = [
        'type' => WarehouseOrderType::class,
        'status' => WarehouseOrderStatus::class,
    ];

    protected static function booted(): void {
        static::creating(function ($model) {
            if (!$model->order_number) {
                $count = static::count() + 1;
                $model->order_number = 'WO-' . str_pad($count, 6, '0', STR_PAD_LEFT);
            }
            if (!$model->created_by) {
                $model->created_by = auth()->id();
            }
        });
    }

    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function items() { return $this->hasMany(WarehouseOrderItem::class); }
    public function activities() { return $this->hasMany(WarehouseActivity::class); }
}
