<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelLog extends Model {
    use HasFactory;

    protected $fillable = ['vehicle_id', 'trip_id', 'fuel_type', 'liters', 'cost_per_liter', 'total_cost', 'odometer_reading', 'filled_at', 'station_name'];
    protected $casts = ['filled_at' => 'datetime'];

    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function trip() { return $this->belongsTo(Trip::class); }
}
