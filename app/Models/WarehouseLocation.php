<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseLocation extends Model {
    use HasFactory;

    protected $fillable = ['warehouse_id', 'zone', 'aisle', 'rack', 'shelf', 'bin', 'capacity', 'occupied'];

    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function inventory() { return $this->hasMany(Inventory::class); }

    public function getNameAttribute(): string {
        return "{$this->zone}-{$this->aisle}{$this->rack}-{$this->shelf}" . ($this->bin ? "-{$this->bin}" : '');
    }
}
