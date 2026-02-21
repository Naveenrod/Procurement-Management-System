<?php
namespace App\Services;

use App\Models\Trip;
use App\Models\Vehicle;
use App\Models\MaintenanceRecord;
use App\Models\FuelLog;
use App\Enums\TripStatus;
use App\Enums\VehicleStatus;
use Illuminate\Support\Facades\DB;

class FleetService
{
    public function startTrip(Trip $trip): Trip
    {
        DB::transaction(function () use ($trip) {
            $trip->update(['status' => TripStatus::InProgress, 'started_at' => now()]);
            $trip->vehicle->update(['status' => VehicleStatus::InUse]);
        });
        return $trip->fresh();
    }

    public function completeTrip(Trip $trip): Trip
    {
        DB::transaction(function () use ($trip) {
            $trip->update(['status' => TripStatus::Completed, 'completed_at' => now()]);
            $trip->vehicle->update(['status' => VehicleStatus::Available]);
        });
        return $trip->fresh();
    }

    public function updateGpsPosition(Trip $trip, float $lat, float $lng): void
    {
        $trip->update(['current_lat' => $lat, 'current_lng' => $lng]);
    }

    public function scheduleMaintenance(Vehicle $vehicle, array $data): MaintenanceRecord
    {
        return MaintenanceRecord::create(array_merge(['vehicle_id' => $vehicle->id], $data));
    }

    public function logFuel(Vehicle $vehicle, array $data): FuelLog
    {
        $log = FuelLog::create(array_merge(['vehicle_id' => $vehicle->id, 'total_cost' => $data['liters'] * $data['cost_per_liter']], $data));
        $vehicle->update(['mileage' => $data['odometer_reading']]);
        return $log;
    }

    public function getFleetStats(): array
    {
        return [
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::where('status', 'available')->count(),
            'in_use_vehicles' => Vehicle::where('status', 'in_use')->count(),
            'maintenance_vehicles' => Vehicle::where('status', 'maintenance')->count(),
            'active_trips' => Trip::where('status', 'in_progress')->count(),
            'completed_trips_this_month' => Trip::where('status', 'completed')->whereMonth('completed_at', now()->month)->count(),
            'fuel_cost_this_month' => FuelLog::whereMonth('filled_at', now()->month)->sum('total_cost'),
            'maintenance_due' => MaintenanceRecord::whereNull('completed_date')->where('scheduled_date', '<=', now()->addDays(7))->count(),
        ];
    }
}
