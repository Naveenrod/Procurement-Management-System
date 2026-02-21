<?php
namespace App\Models;

use App\Enums\TripStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model {
    use HasFactory;

    protected $fillable = ['trip_number', 'vehicle_id', 'driver_id', 'route_id', 'status', 'scheduled_at', 'started_at', 'completed_at', 'current_lat', 'current_lng', 'notes'];
    protected $casts = [
        'status' => TripStatus::class,
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function booted(): void {
        static::creating(function ($model) {
            if (!$model->trip_number) {
                $count = static::count() + 1;
                $model->trip_number = 'TRP-' . str_pad($count, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function driver() { return $this->belongsTo(Driver::class); }
    public function route() { return $this->belongsTo(FleetRoute::class, 'route_id'); }
    public function fuelLogs() { return $this->hasMany(FuelLog::class); }
}
