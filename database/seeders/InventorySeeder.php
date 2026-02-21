<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {
            foreach ($products->take(20) as $product) {
                $qty = rand(10, 200);
                Inventory::firstOrCreate(
                    ['warehouse_id' => $warehouse->id, 'product_id' => $product->id, 'warehouse_location_id' => null],
                    ['quantity_on_hand' => $qty, 'quantity_reserved' => rand(0, min($qty, 20))]
                );
            }
        }
    }
}
