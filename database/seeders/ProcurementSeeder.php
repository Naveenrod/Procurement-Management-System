<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\User;

class ProcurementSeeder extends Seeder
{
    public function run(): void
    {
        $buyer = User::where('email', 'buyer@procurement.test')->first();
        $manager = User::where('email', 'manager@procurement.test')->first();
        $products = Product::all();
        $vendors = Vendor::where('status', 'approved')->get();

        if (!$buyer || $products->isEmpty() || $vendors->isEmpty()) return;

        // Create 10 purchase requisitions
        $statuses = ['draft', 'draft', 'pending_approval', 'approved', 'approved', 'ordered', 'ordered', 'ordered', 'rejected', 'ordered'];
        foreach ($statuses as $i => $status) {
            $req = PurchaseRequisition::create([
                'title' => 'Q' . ($i + 1) . ' Office Supplies Requisition',
                'description' => 'Quarterly requisition for office and IT supplies',
                'department' => ['IT', 'Finance', 'Operations', 'HR', 'Admin'][rand(0, 4)],
                'requested_by' => $buyer->id,
                'required_date' => now()->addDays(rand(7, 30)),
                'status' => $status,
                'priority' => ['low', 'medium', 'high'][rand(0, 2)],
                'approved_by' => in_array($status, ['approved', 'ordered']) ? $manager->id : null,
                'approved_at' => in_array($status, ['approved', 'ordered']) ? now()->subDays(rand(1, 10)) : null,
            ]);

            $total = 0;
            $items = $products->random(rand(2, 4));
            foreach ($items as $product) {
                $qty = rand(5, 20);
                $price = $product->unit_price ?? 25.00;
                PurchaseRequisitionItem::create([
                    'purchase_requisition_id' => $req->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'estimated_unit_price' => $price,
                    'total_price' => $qty * $price,
                ]);
                $total += $qty * $price;
            }
            $req->update(['total_amount' => $total]);
        }

        // Create 8 purchase orders
        $poStatuses = ['draft', 'approved', 'sent', 'sent', 'sent', 'received', 'received', 'received'];
        foreach ($poStatuses as $i => $status) {
            $vendor = $vendors->random();
            $po = PurchaseOrder::create([
                'vendor_id' => $vendor->id,
                'order_date' => now()->subDays(rand(1, 60)),
                'expected_delivery_date' => now()->addDays(rand(7, 30)),
                'status' => $status,
                'created_by' => $buyer->id,
                'approved_by' => in_array($status, ['approved', 'sent', 'received']) ? $manager->id : null,
                'approved_at' => in_array($status, ['approved', 'sent', 'received']) ? now()->subDays(rand(1, 5)) : null,
                'notes' => 'Purchase order for operational supplies',
            ]);

            $subtotal = 0;
            $poItems = $products->random(rand(2, 4));
            foreach ($poItems as $product) {
                $qty = rand(5, 15);
                $price = $product->unit_price ?? 25.00;
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'total_price' => $qty * $price,
                    'received_quantity' => $status === 'received' ? $qty : 0,
                ]);
                $subtotal += $qty * $price;
            }
            $tax = $subtotal * 0.1;
            $po->update(['subtotal' => $subtotal, 'tax_amount' => $tax, 'total_amount' => $subtotal + $tax]);

            // Create goods receipt for received POs
            if ($status === 'received') {
                $receiver = User::where('email', 'warehouse@procurement.test')->first();
                $gr = GoodsReceipt::create([
                    'purchase_order_id' => $po->id,
                    'received_by' => $receiver?->id ?? $buyer->id,
                    'received_at' => now()->subDays(rand(1, 10)),
                    'status' => 'complete',
                ]);

                foreach ($po->items as $poi) {
                    GoodsReceiptItem::create([
                        'goods_receipt_id' => $gr->id,
                        'purchase_order_item_id' => $poi->id,
                        'quantity_received' => $poi->quantity,
                        'quantity_accepted' => $poi->quantity,
                        'quantity_rejected' => 0,
                    ]);
                }

                // Create invoice for last received order
                if ($i === 7) {
                    Invoice::create([
                        'vendor_id' => $vendor->id,
                        'purchase_order_id' => $po->id,
                        'goods_receipt_id' => $gr->id,
                        'invoice_date' => now()->subDays(rand(1, 7)),
                        'due_date' => now()->addDays(30),
                        'subtotal' => $po->subtotal,
                        'tax_amount' => $po->tax_amount,
                        'total_amount' => $po->total_amount,
                        'status' => 'approved',
                        'three_way_match_status' => 'matched',
                        'submitted_by' => $buyer->id,
                        'approved_by' => $manager->id,
                    ]);
                }
            }
        }
    }
}
