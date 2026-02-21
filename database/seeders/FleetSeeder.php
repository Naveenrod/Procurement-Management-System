<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\FleetRoute;
use App\Models\Trip;

class FleetSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            ['registration_number' => 'ABC-001', 'make' => 'Isuzu', 'model' => 'N-Series', 'year' => 2021, 'type' => 'truck', 'status' => 'available', 'mileage' => 45000, 'fuel_type' => 'diesel', 'insurance_expiry' => now()->addMonths(6), 'registration_expiry' => now()->addMonths(3)],
            ['registration_number' => 'ABC-002', 'make' => 'Ford', 'model' => 'Transit', 'year' => 2022, 'type' => 'van', 'status' => 'available', 'mileage' => 28000, 'fuel_type' => 'diesel', 'insurance_expiry' => now()->addMonths(8)],
            ['registration_number' => 'ABC-003', 'make' => 'Hino', 'model' => '300 Series', 'year' => 2020, 'type' => 'truck', 'status' => 'maintenance', 'mileage' => 87000, 'fuel_type' => 'diesel', 'insurance_expiry' => now()->addMonths(2)],
            ['registration_number' => 'ABC-004', 'make' => 'Toyota', 'model' => 'HiAce', 'year' => 2023, 'type' => 'van', 'status' => 'available', 'mileage' => 12000, 'fuel_type' => 'petrol'],
            ['registration_number' => 'ABC-005', 'make' => 'Mitsubishi', 'model' => 'Fuso', 'year' => 2019, 'type' => 'truck', 'status' => 'available', 'mileage' => 120000, 'fuel_type' => 'diesel'],
        ];

        foreach ($vehicles as $data) {
            Vehicle::firstOrCreate(['registration_number' => $data['registration_number']], $data);
        }

        $drivers = [
            ['name' => 'John Smith', 'phone' => '0400-001-001', 'license_number' => 'LIC-001', 'license_expiry' => now()->addMonths(18), 'status' => 'available'],
            ['name' => 'Mary Johnson', 'phone' => '0400-001-002', 'license_number' => 'LIC-002', 'license_expiry' => now()->addMonths(6), 'status' => 'available'],
            ['name' => 'Bob Williams', 'phone' => '0400-001-003', 'license_number' => 'LIC-003', 'license_expiry' => now()->addMonths(24), 'status' => 'available'],
            ['name' => 'Alice Brown', 'phone' => '0400-001-004', 'license_number' => 'LIC-004', 'license_expiry' => now()->addMonths(12), 'status' => 'on_trip'],
            ['name' => 'Charlie Davis', 'phone' => '0400-001-005', 'license_number' => 'LIC-005', 'license_expiry' => now()->addMonths(3), 'status' => 'available'],
        ];

        foreach ($drivers as $data) {
            Driver::firstOrCreate(['license_number' => $data['license_number']], $data);
        }

        $routes = [
            ['name' => 'Sydney to Melbourne', 'origin' => 'Sydney', 'destination' => 'Melbourne', 'distance_km' => 878, 'estimated_hours' => 9.5],
            ['name' => 'Sydney to Brisbane', 'origin' => 'Sydney', 'destination' => 'Brisbane', 'distance_km' => 915, 'estimated_hours' => 10.5],
            ['name' => 'Sydney CBD Delivery', 'origin' => 'Main Warehouse', 'destination' => 'Sydney CBD', 'distance_km' => 25, 'estimated_hours' => 1.5],
            ['name' => 'North Shore Loop', 'origin' => 'Main Warehouse', 'destination' => 'North Shore', 'distance_km' => 35, 'estimated_hours' => 2.0],
            ['name' => 'Western Suburbs Run', 'origin' => 'Main Warehouse', 'destination' => 'Parramatta', 'distance_km' => 45, 'estimated_hours' => 2.5],
        ];

        foreach ($routes as $data) {
            FleetRoute::create($data);
        }

        // Create some trips
        $veh = Vehicle::where('status', 'available')->first();
        $drv = Driver::where('status', 'available')->first();
        $route = FleetRoute::first();

        if ($veh && $drv && $route) {
            Trip::create([
                'vehicle_id' => $veh->id,
                'driver_id' => $drv->id,
                'route_id' => $route->id,
                'status' => 'scheduled',
                'scheduled_at' => now()->addDay(),
            ]);

            Trip::create([
                'vehicle_id' => $veh->id,
                'driver_id' => $drv->id,
                'route_id' => $route->id,
                'status' => 'completed',
                'scheduled_at' => now()->subDays(3),
                'started_at' => now()->subDays(3)->addHour(),
                'completed_at' => now()->subDays(3)->addHours(11),
            ]);
        }
    }
}
