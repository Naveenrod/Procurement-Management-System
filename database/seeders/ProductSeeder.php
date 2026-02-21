<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::first();

        $products = [
            ['name' => 'A4 Copy Paper (500 sheets)', 'sku' => 'OFF-001', 'unit_price' => 8.50, 'unit_of_measure' => 'ream', 'reorder_point' => 50],
            ['name' => 'Ballpoint Pens (Box of 50)', 'sku' => 'OFF-002', 'unit_price' => 15.00, 'unit_of_measure' => 'box', 'reorder_point' => 20],
            ['name' => 'Stapler (Heavy Duty)', 'sku' => 'OFF-003', 'unit_price' => 25.00, 'unit_of_measure' => 'each', 'reorder_point' => 10],
            ['name' => 'HP LaserJet Toner (Black)', 'sku' => 'IT-001', 'unit_price' => 89.00, 'unit_of_measure' => 'each', 'reorder_point' => 5],
            ['name' => 'USB-C Cable (2m)', 'sku' => 'IT-002', 'unit_price' => 18.00, 'unit_of_measure' => 'each', 'reorder_point' => 15],
            ['name' => 'Wireless Mouse', 'sku' => 'IT-003', 'unit_price' => 45.00, 'unit_of_measure' => 'each', 'reorder_point' => 10],
            ['name' => 'Mechanical Keyboard', 'sku' => 'IT-004', 'unit_price' => 120.00, 'unit_of_measure' => 'each', 'reorder_point' => 5],
            ['name' => '27" Monitor', 'sku' => 'IT-005', 'unit_price' => 350.00, 'unit_of_measure' => 'each', 'reorder_point' => 3],
            ['name' => 'Network Switch 24-port', 'sku' => 'IT-006', 'unit_price' => 280.00, 'unit_of_measure' => 'each', 'reorder_point' => 2],
            ['name' => 'Safety Gloves (Medium)', 'sku' => 'IND-001', 'unit_price' => 12.00, 'unit_of_measure' => 'pair', 'reorder_point' => 50],
            ['name' => 'Safety Helmet (Yellow)', 'sku' => 'IND-002', 'unit_price' => 35.00, 'unit_of_measure' => 'each', 'reorder_point' => 20],
            ['name' => 'High-Vis Vest (L)', 'sku' => 'IND-003', 'unit_price' => 22.00, 'unit_of_measure' => 'each', 'reorder_point' => 25],
            ['name' => 'Steel Toe Boots (Size 10)', 'sku' => 'IND-004', 'unit_price' => 85.00, 'unit_of_measure' => 'pair', 'reorder_point' => 10],
            ['name' => 'Power Drill (Cordless)', 'sku' => 'IND-005', 'unit_price' => 175.00, 'unit_of_measure' => 'each', 'reorder_point' => 5],
            ['name' => 'Angle Grinder 115mm', 'sku' => 'IND-006', 'unit_price' => 95.00, 'unit_of_measure' => 'each', 'reorder_point' => 3],
            ['name' => 'Mild Steel Sheet 2mm', 'sku' => 'RAW-001', 'unit_price' => 45.00, 'unit_of_measure' => 'sheet', 'reorder_point' => 30],
            ['name' => 'Stainless Steel Rod 10mm', 'sku' => 'RAW-002', 'unit_price' => 28.00, 'unit_of_measure' => 'meter', 'reorder_point' => 50],
            ['name' => 'Aluminium Extrusion 40x40', 'sku' => 'RAW-003', 'unit_price' => 38.00, 'unit_of_measure' => 'meter', 'reorder_point' => 20],
            ['name' => 'PVC Pipe 50mm (3m)', 'sku' => 'RAW-004', 'unit_price' => 15.00, 'unit_of_measure' => 'each', 'reorder_point' => 40],
            ['name' => 'Epoxy Resin (1L)', 'sku' => 'RAW-005', 'unit_price' => 55.00, 'unit_of_measure' => 'liter', 'reorder_point' => 10],
            ['name' => 'Box of Nitrile Gloves (100)', 'sku' => 'LAB-001', 'unit_price' => 18.00, 'unit_of_measure' => 'box', 'reorder_point' => 30],
            ['name' => 'Safety Goggles', 'sku' => 'LAB-002', 'unit_price' => 14.00, 'unit_of_measure' => 'each', 'reorder_point' => 20],
            ['name' => 'First Aid Kit (Large)', 'sku' => 'SAF-001', 'unit_price' => 95.00, 'unit_of_measure' => 'each', 'reorder_point' => 5],
            ['name' => 'Fire Extinguisher CO2 (5kg)', 'sku' => 'SAF-002', 'unit_price' => 130.00, 'unit_of_measure' => 'each', 'reorder_point' => 5],
            ['name' => 'Laptop Stand Adjustable', 'sku' => 'OFF-004', 'unit_price' => 48.00, 'unit_of_measure' => 'each', 'reorder_point' => 8],
            ['name' => 'Desk Organizer', 'sku' => 'OFF-005', 'unit_price' => 22.00, 'unit_of_measure' => 'each', 'reorder_point' => 10],
            ['name' => 'Whiteboard Markers (8 pack)', 'sku' => 'OFF-006', 'unit_price' => 12.00, 'unit_of_measure' => 'pack', 'reorder_point' => 20],
            ['name' => 'Filing Cabinet (4 drawer)', 'sku' => 'OFF-007', 'unit_price' => 285.00, 'unit_of_measure' => 'each', 'reorder_point' => 2],
            ['name' => 'Ethernet Cable Cat6 (10m)', 'sku' => 'IT-007', 'unit_price' => 22.00, 'unit_of_measure' => 'each', 'reorder_point' => 20],
            ['name' => 'UPS Power Backup 1000VA', 'sku' => 'IT-008', 'unit_price' => 195.00, 'unit_of_measure' => 'each', 'reorder_point' => 3],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['sku' => $data['sku']],
                array_merge($data, ['category_id' => $category?->id, 'is_active' => true])
            );
        }
    }
}
