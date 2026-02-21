<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CycleCount extends Model {
    use HasFactory;

    protected $fillable = ['count_number', 'warehouse_id', 'status', 'created_by', 'completed_at'];
    protected $casts = ['completed_at' => 'datetime'];

    protected static function booted(): void {
        static::creating(function ($model) {
            if (!$model->count_number) {
                $count = static::count() + 1;
                $model->count_number = 'CC-' . str_pad($count, 6, '0', STR_PAD_LEFT);
            }
            if (!$model->created_by && auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }

    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function items() { return $this->hasMany(CycleCountItem::class); }
}
