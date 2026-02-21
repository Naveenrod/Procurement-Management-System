<?php
namespace App\Models;

use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model {
    use HasFactory;

    protected $fillable = ['registration_number', 'make', 'model', 'year', 'type', 'status', 'mileage', 'fuel_type', 'insurance_expiry', 'registration_expiry'];
    protected $casts = [
        'status' => VehicleStatus::class,
        'insurance_expiry' => 'date',
        'registration_expiry' => 'date',
    ];

    public function trips() { return $this->hasMany(Trip::class); }
    public function maintenanceRecords() { return $this->hasMany(MaintenanceRecord::class); }
    public function fuelLogs() { return $this->hasMany(FuelLog::class); }
}
