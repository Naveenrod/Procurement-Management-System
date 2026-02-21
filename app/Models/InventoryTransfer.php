<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransfer extends Model {
    use HasFactory;

    protected $fillable = ['transfer_number', 'from_warehouse_id', 'to_warehouse_id', 'status', 'requested_by', 'approved_by', 'notes'];

    protected static function booted(): void {
        static::creating(function ($model) {
            if (!$model->transfer_number) {
                $count = static::count() + 1;
                $model->transfer_number = 'TRF-' . str_pad($count, 6, '0', STR_PAD_LEFT);
            }
            if (!$model->requested_by && auth()->check()) {
                $model->requested_by = auth()->id();
            }
        });
    }

    public function fromWarehouse() { return $this->belongsTo(Warehouse::class, 'from_warehouse_id'); }
    public function toWarehouse() { return $this->belongsTo(Warehouse::class, 'to_warehouse_id'); }
    public function requester() { return $this->belongsTo(User::class, 'requested_by'); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
    public function items() { return $this->hasMany(InventoryTransferItem::class); }
}
