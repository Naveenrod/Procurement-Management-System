<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['code' => 'OFF', 'name' => 'Office Supplies', 'children' => [
                ['code' => 'OFF-PAP', 'name' => 'Paper & Stationery'],
                ['code' => 'OFF-PRT', 'name' => 'Printer Supplies'],
                ['code' => 'OFF-FUR', 'name' => 'Office Furniture'],
            ]],
            ['code' => 'IT', 'name' => 'IT Equipment', 'children' => [
                ['code' => 'IT-CMP', 'name' => 'Computers & Laptops'],
                ['code' => 'IT-NET', 'name' => 'Networking Equipment'],
                ['code' => 'IT-PER', 'name' => 'Peripherals'],
            ]],
            ['code' => 'IND', 'name' => 'Industrial Equipment', 'children' => [
                ['code' => 'IND-PWR', 'name' => 'Power Tools'],
                ['code' => 'IND-SAF', 'name' => 'Safety Equipment'],
                ['code' => 'IND-HVY', 'name' => 'Heavy Machinery'],
            ]],
            ['code' => 'RAW', 'name' => 'Raw Materials', 'children' => [
                ['code' => 'RAW-STL', 'name' => 'Steel & Metals'],
                ['code' => 'RAW-PLS', 'name' => 'Plastics & Polymers'],
                ['code' => 'RAW-CHM', 'name' => 'Chemicals'],
            ]],
            ['code' => 'SVC', 'name' => 'Services', 'children' => [
                ['code' => 'SVC-CON', 'name' => 'Consulting'],
                ['code' => 'SVC-MNT', 'name' => 'Maintenance Services'],
                ['code' => 'SVC-LOG', 'name' => 'Logistics'],
            ]],
        ];

        foreach ($categories as $parentData) {
            $children = $parentData['children'];
            unset($parentData['children']);
            $parent = Category::firstOrCreate(['code' => $parentData['code']], $parentData);
            foreach ($children as $childData) {
                $childData['parent_id'] = $parent->id;
                Category::firstOrCreate(['code' => $childData['code']], $childData);
            }
        }
    }
}
