<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceipt;
use App\Models\Invoice;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Services\ThreeWayMatchService;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
        $this->seed(\Database\Seeders\UserSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\ProductSeeder::class);
        $this->seed(\Database\Seeders\VendorSeeder::class);
        $this->seed(\Database\Seeders\WarehouseSeeder::class);
        $this->seed(\Database\Seeders\InventorySeeder::class);
        $this->seed(\Database\Seeders\ProcurementSeeder::class);
    }

    // ===================== THREE-WAY MATCH SERVICE =====================

    public function test_three_way_match_service_exists(): void
    {
        $service = app(ThreeWayMatchService::class);
        $this->assertInstanceOf(ThreeWayMatchService::class, $service);
    }

    public function test_three_way_match_on_invoice_with_po_and_receipt(): void
    {
        $invoice = Invoice::whereNotNull('purchase_order_id')
            ->whereNotNull('goods_receipt_id')
            ->first();

        if (!$invoice) {
            $this->markTestSkipped('No invoice with PO and GR found');
        }

        $service = app(ThreeWayMatchService::class);
        $result = $service->performMatch($invoice);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertContains($result['status'], ['matched', 'mismatch']);
    }

    // ===================== INVENTORY SERVICE =====================

    public function test_inventory_service_exists(): void
    {
        $service = app(InventoryService::class);
        $this->assertInstanceOf(InventoryService::class, $service);
    }

    // ===================== MODEL TESTS =====================

    public function test_vendor_code_auto_generated(): void
    {
        $vendor = Vendor::create([
            'name' => 'Auto Code Vendor',
            'contact_person' => 'Test Person',
            'email' => 'autocode@test.com',
            'phone' => '+1-555-0000',
            'payment_terms' => 'net30',
        ]);

        $this->assertNotNull($vendor->code);
        $this->assertStringStartsWith('VND-', $vendor->code);
    }

    public function test_purchase_requisition_number_auto_generated(): void
    {
        $user = User::where('email', 'buyer@procurement.test')->first();
        $product = Product::first();

        $req = \App\Models\PurchaseRequisition::create([
            'title' => 'Auto Number Test',
            'department' => 'IT',
            'requested_by' => $user->id,
            'required_date' => now()->addDays(7),
            'status' => 'draft',
            'priority' => 'medium',
        ]);

        $this->assertNotNull($req->requisition_number);
        $this->assertStringStartsWith('REQ-', $req->requisition_number);
    }

    public function test_purchase_order_number_auto_generated(): void
    {
        $user = User::where('email', 'buyer@procurement.test')->first();
        $vendor = Vendor::where('status', 'approved')->first();

        $po = PurchaseOrder::create([
            'vendor_id' => $vendor->id,
            'order_date' => now(),
            'expected_delivery_date' => now()->addDays(14),
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        $this->assertNotNull($po->po_number);
        $this->assertStringStartsWith('PO-', $po->po_number);
    }

    // ===================== ENUM TESTS =====================

    public function test_vendor_status_enum_has_required_cases(): void
    {
        $cases = \App\Enums\VendorStatus::cases();
        $values = array_map(fn($c) => $c->value, $cases);
        $this->assertContains('pending', $values);
        $this->assertContains('approved', $values);
        $this->assertContains('suspended', $values);
        $this->assertContains('blacklisted', $values);
    }

    public function test_requisition_status_enum_has_required_cases(): void
    {
        $cases = \App\Enums\RequisitionStatus::cases();
        $values = array_map(fn($c) => $c->value, $cases);
        $this->assertContains('draft', $values);
        $this->assertContains('pending_approval', $values);
        $this->assertContains('approved', $values);
        $this->assertContains('rejected', $values);
        $this->assertContains('ordered', $values);
    }

    public function test_purchase_order_status_enum_has_required_cases(): void
    {
        $cases = \App\Enums\PurchaseOrderStatus::cases();
        $values = array_map(fn($c) => $c->value, $cases);
        $this->assertContains('draft', $values);
        $this->assertContains('approved', $values);
        $this->assertContains('sent', $values);
        $this->assertContains('received', $values);
        $this->assertContains('cancelled', $values);
    }

    public function test_enum_labels_return_strings(): void
    {
        foreach (\App\Enums\VendorStatus::cases() as $case) {
            $this->assertIsString($case->label());
            $this->assertIsString($case->color());
        }
        foreach (\App\Enums\RequisitionStatus::cases() as $case) {
            $this->assertIsString($case->label());
            $this->assertIsString($case->color());
        }
        foreach (\App\Enums\PurchaseOrderStatus::cases() as $case) {
            $this->assertIsString($case->label());
            $this->assertIsString($case->color());
        }
    }

    // ===================== RELATIONSHIP TESTS =====================

    public function test_vendor_has_contacts(): void
    {
        $vendor = Vendor::has('contacts')->first();
        $this->assertNotNull($vendor);
        $this->assertGreaterThan(0, $vendor->contacts->count());
    }

    public function test_purchase_order_belongs_to_vendor(): void
    {
        $po = PurchaseOrder::first();
        $this->assertNotNull($po->vendor);
        $this->assertInstanceOf(Vendor::class, $po->vendor);
    }

    public function test_purchase_order_has_items(): void
    {
        $po = PurchaseOrder::has('items')->first();
        $this->assertNotNull($po);
        $this->assertGreaterThan(0, $po->items->count());
    }

    public function test_inventory_belongs_to_warehouse_and_product(): void
    {
        $inv = Inventory::first();
        if (!$inv) {
            $this->markTestSkipped('No inventory records found');
        }
        $this->assertNotNull($inv->warehouse);
        $this->assertNotNull($inv->product);
    }

    // ===================== SEEDER DATA INTEGRITY =====================

    public function test_roles_were_seeded(): void
    {
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
        $this->assertDatabaseHas('roles', ['name' => 'manager']);
        $this->assertDatabaseHas('roles', ['name' => 'buyer']);
        $this->assertDatabaseHas('roles', ['name' => 'warehouse_worker']);
        $this->assertDatabaseHas('roles', ['name' => 'supplier']);
    }

    public function test_users_have_correct_roles(): void
    {
        $admin = User::where('email', 'admin@procurement.test')->first();
        $this->assertTrue($admin->hasRole('admin'));

        $buyer = User::where('email', 'buyer@procurement.test')->first();
        $this->assertTrue($buyer->hasRole('buyer'));

        $supplier = User::where('email', 'supplier@procurement.test')->first();
        $this->assertTrue($supplier->hasRole('supplier'));
    }

    public function test_categories_seeded_with_hierarchy(): void
    {
        $parent = \App\Models\Category::whereNull('parent_id')->first();
        $this->assertNotNull($parent);
        $this->assertGreaterThan(0, $parent->children->count());
    }

    public function test_products_seeded(): void
    {
        $this->assertGreaterThan(10, Product::count());
    }

    public function test_vendors_seeded_with_correct_status(): void
    {
        $this->assertGreaterThan(0, Vendor::where('status', 'approved')->count());
        $this->assertGreaterThan(0, Vendor::where('status', 'pending')->count());
    }

    public function test_supplier_user_linked_to_vendor(): void
    {
        $supplier = User::where('email', 'supplier@procurement.test')->first();
        $this->assertNotNull($supplier->vendor_id);
    }
}
