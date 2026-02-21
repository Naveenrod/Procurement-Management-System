<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model {
    use HasFactory;

    protected $fillable = ['name', 'code', 'address', 'city', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    protected static function booted(): void {
        static::creating(function ($model) {
            if (!$model->code) {
                $count = static::count() + 1;
                $model->code = 'WH-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function locations() { return $this->hasMany(WarehouseLocation::class); }
    public function inventory() { return $this->hasMany(Inventory::class); }
    public function orders() { return $this->hasMany(WarehouseOrder::class); }
    public function activities() { return $this->hasMany(WarehouseActivity::class); }
}
