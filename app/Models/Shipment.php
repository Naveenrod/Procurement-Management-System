<?php
namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = ['tracking_number', 'purchase_order_id', 'carrier', 'status', 'shipped_at', 'estimated_arrival', 'delivered_at', 'notes'];
    protected $casts = [
        'status' => ShipmentStatus::class,
        'shipped_at' => 'datetime',
        'estimated_arrival' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected static function booted(): void {
        static::creating(function ($model) {
            if (!$model->tracking_number) {
                $count = static::count() + 1;
                $model->tracking_number = 'SHP-' . str_pad($count, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class); }
}
