<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryTransaction extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = ['inventory_id', 'type', 'quantity', 'reference_type', 'reference_id', 'notes', 'created_by'];

    protected static function booted(): void {
        static::creating(function ($model) {
            if (!$model->created_by && auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }

    public function inventory() { return $this->belongsTo(Inventory::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
