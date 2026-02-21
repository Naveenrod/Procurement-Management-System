<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            ['name' => 'Main Warehouse', 'address' => '100 Industrial Blvd, Sydney NSW 2000', 'city' => 'Sydney', 'is_active' => true],
            ['name' => 'North Depot', 'address' => '50 Commerce Drive, Brisbane QLD 4000', 'city' => 'Brisbane', 'is_active' => true],
            ['name' => 'South Depot', 'address' => '200 Logistics Way, Melbourne VIC 3000', 'city' => 'Melbourne', 'is_active' => true],
        ];

        foreach ($warehouses as $data) {
            $wh = Warehouse::create($data);

            // Create locations: zones A, B, C with aisles 1-3, racks 1-2, shelves 1-3
            foreach (['A', 'B', 'C'] as $zone) {
                foreach (['1', '2', '3'] as $aisle) {
                    foreach (['1', '2'] as $rack) {
                        foreach (['1', '2', '3'] as $shelf) {
                            WarehouseLocation::create([
                                'warehouse_id' => $wh->id,
                                'zone' => $zone,
                                'aisle' => $aisle,
                                'rack' => $rack,
                                'shelf' => $shelf,
                                'capacity' => 100,
                                'occupied' => 0,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
