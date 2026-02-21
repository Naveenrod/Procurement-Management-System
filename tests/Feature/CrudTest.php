<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseOrder;
use App\Models\Budget;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\FleetRoute;
use App\Models\Warehouse;
use App\Models\Contract;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $buyer;
    protected User $manager;

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
        $this->seed(\Database\Seeders\BudgetSeeder::class);
        $this->seed(\Database\Seeders\FleetSeeder::class);

        $this->admin = User::where('email', 'admin@procurement.test')->first();
        $this->buyer = User::where('email', 'buyer@procurement.test')->first();
        $this->manager = User::where('email', 'manager@procurement.test')->first();
    }

    // ===================== VENDOR CRUD =====================

    public function test_can_create_vendor(): void
    {
        $response = $this->actingAs($this->admin)->post('/vendors', [
            'name' => 'Test Vendor Corp',
            'contact_person' => 'Jane Doe',
            'email' => 'test@testvendor.com',
            'phone' => '+1-555-9999',
            'country' => 'USA',
            'payment_terms' => 'net30',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('vendors', ['email' => 'test@testvendor.com']);
    }

    public function test_can_update_vendor(): void
    {
        $vendor = Vendor::first();
        $response = $this->actingAs($this->admin)->put("/vendors/{$vendor->id}", [
            'name' => 'Updated Vendor Name',
            'contact_person' => $vendor->contact_person,
            'email' => $vendor->email,
            'phone' => $vendor->phone,
            'payment_terms' => 'net60',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('vendors', ['id' => $vendor->id, 'name' => 'Updated Vendor Name']);
    }

    public function test_can_approve_vendor(): void
    {
        $vendor = Vendor::where('status', 'pending')->first();
        if (!$vendor) {
            $this->markTestSkipped('No pending vendor found');
        }
        $response = $this->actingAs($this->admin)->post("/vendors/{$vendor->id}/approve");
        $response->assertRedirect();
        $vendor->refresh();
        $this->assertEquals('approved', $vendor->status->value);
    }

    // ===================== REQUISITION CRUD =====================

    public function test_can_create_requisition(): void
    {
        $product = Product::first();
        $response = $this->actingAs($this->buyer)->post('/procurement/requisitions', [
            'title' => 'Test Requisition',
            'description' => 'Testing requisition creation',
            'department' => 'IT',
            'priority' => 'medium',
            'required_date' => now()->addDays(14)->format('Y-m-d'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'estimated_unit_price' => 25.00,
                ],
            ],
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('purchase_requisitions', ['title' => 'Test Requisition']);
    }

    public function test_can_update_draft_requisition(): void
    {
        $req = PurchaseRequisition::where('status', 'draft')->first();
        if (!$req) {
            $this->markTestSkipped('No draft requisition found');
        }
        $req->load('items');
        $items = $req->items->map(fn($item) => [
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'unit_price' => $item->estimated_unit_price,
        ])->toArray();

        $response = $this->actingAs($this->buyer)->put("/procurement/requisitions/{$req->id}", [
            'title' => 'Updated Requisition Title',
            'description' => $req->description,
            'department' => $req->department,
            'priority' => 'high',
            'required_date' => now()->addDays(30)->format('Y-m-d'),
            'items' => $items,
        ]);
        $response->assertRedirect();
        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('purchase_requisitions', ['id' => $req->id, 'title' => 'Updated Requisition Title']);
    }

    // ===================== PURCHASE ORDER CRUD =====================

    public function test_can_create_purchase_order(): void
    {
        $vendor = Vendor::where('status', 'approved')->first();
        $product = Product::first();
        $response = $this->actingAs($this->buyer)->post('/procurement/purchase-orders', [
            'vendor_id' => $vendor->id,
            'expected_delivery_date' => now()->addDays(14)->format('Y-m-d'),
            'notes' => 'Test PO',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                    'unit_price' => 50.00,
                ],
            ],
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('purchase_orders', ['vendor_id' => $vendor->id]);
    }

    // ===================== BUDGET CRUD =====================

    public function test_can_create_budget(): void
    {
        $response = $this->actingAs($this->admin)->post('/procurement/budgets', [
            'department' => 'Marketing',
            'fiscal_year' => 2026,
            'allocated_amount' => 50000.00,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('budgets', ['department' => 'Marketing']);
    }

    public function test_can_update_budget(): void
    {
        $budget = Budget::first();
        if (!$budget) {
            $this->markTestSkipped('No budget found');
        }
        $response = $this->actingAs($this->admin)->put("/procurement/budgets/{$budget->id}", [
            'department' => $budget->department,
            'fiscal_year' => $budget->fiscal_year,
            'allocated_amount' => 100000.00,
        ]);
        $response->assertRedirect();
    }

    // ===================== WAREHOUSE CRUD =====================

    public function test_can_create_warehouse(): void
    {
        $response = $this->actingAs($this->admin)->post('/inventory/warehouses', [
            'name' => 'Test Warehouse',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'is_active' => true,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('warehouses', ['name' => 'Test Warehouse']);
    }

    // ===================== FLEET - VEHICLE CRUD =====================

    public function test_can_create_vehicle(): void
    {
        $response = $this->actingAs($this->admin)->post('/fleet/vehicles', [
            'registration_number' => 'TEST-001',
            'make' => 'Toyota',
            'model' => 'Hilux',
            'year' => 2024,
            'type' => 'truck',
            'status' => 'available',
            'fuel_type' => 'diesel',
            'insurance_expiry' => now()->addYear()->format('Y-m-d'),
            'registration_expiry' => now()->addYear()->format('Y-m-d'),
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('vehicles', ['registration_number' => 'TEST-001']);
    }

    // ===================== FLEET - DRIVER CRUD =====================

    public function test_can_create_driver(): void
    {
        $response = $this->actingAs($this->admin)->post('/fleet/drivers', [
            'name' => 'Test Driver',
            'phone' => '+1-555-0000',
            'license_number' => 'DL-TEST-001',
            'license_expiry' => now()->addYear()->format('Y-m-d'),
            'status' => 'available',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('drivers', ['name' => 'Test Driver']);
    }

    // ===================== FLEET - ROUTE CRUD =====================

    public function test_can_create_route(): void
    {
        $response = $this->actingAs($this->admin)->post('/fleet/routes', [
            'name' => 'Test Route',
            'origin' => 'City A',
            'destination' => 'City B',
            'distance_km' => 150.5,
            'estimated_hours' => 2.5,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('routes', ['name' => 'Test Route']);
    }

    // ===================== CONTRACT CRUD =====================

    public function test_can_create_contract(): void
    {
        $vendor = Vendor::first();
        $response = $this->actingAs($this->admin)->post('/contracts', [
            'vendor_id' => $vendor->id,
            'title' => 'Test Contract',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addYear()->format('Y-m-d'),
            'value' => 100000.00,
            'status' => 'draft',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('contracts', ['title' => 'Test Contract']);
    }

    // ===================== DELETE OPERATIONS =====================

    public function test_can_delete_vendor(): void
    {
        $vendor = Vendor::where('status', 'pending')->first();
        if (!$vendor) {
            $this->markTestSkipped('No deletable vendor found');
        }
        $response = $this->actingAs($this->admin)->delete("/vendors/{$vendor->id}");
        $response->assertRedirect();
        $this->assertSoftDeleted('vendors', ['id' => $vendor->id]);
    }
}
