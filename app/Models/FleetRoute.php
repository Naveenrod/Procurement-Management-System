<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetRoute extends Model {
    use HasFactory;

    protected $table = 'routes';
    protected $fillable = ['name', 'code', 'origin', 'destination', 'distance_km', 'estimated_hours'];

    protected static function booted(): void {
        static::creating(function ($model) {
            if (!$model->code) {
                $count = static::count() + 1;
                $model->code = 'RTE-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function trips() { return $this->hasMany(Trip::class, 'route_id'); }
}
