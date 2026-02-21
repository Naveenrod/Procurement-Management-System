<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceipt;
use App\Models\Invoice;
use App\Models\Budget;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\FleetRoute;
use App\Models\Trip;
use App\Models\Warehouse;
use App\Models\Contract;
use App\Models\Inventory;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $manager;
    protected User $buyer;
    protected User $warehouseUser;
    protected User $supplierUser;

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
        $this->manager = User::where('email', 'manager@procurement.test')->first();
        $this->buyer = User::where('email', 'buyer@procurement.test')->first();
        $this->warehouseUser = User::where('email', 'warehouse@procurement.test')->first();
        $this->supplierUser = User::where('email', 'supplier@procurement.test')->first();
    }

    // ===================== AUTH TESTS =====================

    public function test_login_page_loads(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_register_page_loads(): void
    {
        $this->get('/register')->assertStatus(200);
    }

    public function test_unauthenticated_users_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/procurement/requisitions')->assertRedirect('/login');
        $this->get('/vendors')->assertRedirect('/login');
    }

    public function test_quick_login_works_in_testing(): void
    {
        $response = $this->post('/quick-login', ['user_email' => 'admin@procurement.test']);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->admin);
    }

    // ===================== DASHBOARD =====================

    public function test_admin_can_access_dashboard(): void
    {
        $this->actingAs($this->admin)->get('/dashboard')->assertStatus(200);
    }

    public function test_manager_can_access_dashboard(): void
    {
        $this->actingAs($this->manager)->get('/dashboard')->assertStatus(200);
    }

    public function test_buyer_can_access_dashboard(): void
    {
        $this->actingAs($this->buyer)->get('/dashboard')->assertStatus(200);
    }

    public function test_warehouse_user_can_access_dashboard(): void
    {
        $this->actingAs($this->warehouseUser)->get('/dashboard')->assertStatus(200);
    }

    // ===================== PROCUREMENT - REQUISITIONS =====================

    public function test_requisitions_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/procurement/requisitions')->assertStatus(200);
    }

    public function test_requisitions_create_form_loads(): void
    {
        $this->actingAs($this->buyer)->get('/procurement/requisitions/create')->assertStatus(200);
    }

    public function test_requisitions_show_loads(): void
    {
        $req = PurchaseRequisition::first();
        $this->actingAs($this->admin)->get("/procurement/requisitions/{$req->id}")->assertStatus(200);
    }

    public function test_requisitions_edit_loads(): void
    {
        $req = PurchaseRequisition::where('status', 'draft')->first();
        $this->actingAs($this->buyer)->get("/procurement/requisitions/{$req->id}/edit")->assertStatus(200);
    }

    // ===================== PROCUREMENT - PURCHASE ORDERS =====================

    public function test_purchase_orders_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/procurement/purchase-orders')->assertStatus(200);
    }

    public function test_purchase_orders_create_loads(): void
    {
        $this->actingAs($this->buyer)->get('/procurement/purchase-orders/create')->assertStatus(200);
    }

    public function test_purchase_orders_show_loads(): void
    {
        $po = PurchaseOrder::first();
        $this->actingAs($this->admin)->get("/procurement/purchase-orders/{$po->id}")->assertStatus(200);
    }

    // ===================== PROCUREMENT - GOODS RECEIPTS =====================

    public function test_goods_receipts_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/procurement/goods-receipts')->assertStatus(200);
    }

    public function test_goods_receipts_show_loads(): void
    {
        $gr = GoodsReceipt::first();
        if ($gr) {
            $this->actingAs($this->admin)->get("/procurement/goods-receipts/{$gr->id}")->assertStatus(200);
        } else {
            $this->assertTrue(true); // Skip if no data
        }
    }

    // ===================== PROCUREMENT - INVOICES =====================

    public function test_invoices_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/procurement/invoices')->assertStatus(200);
    }

    public function test_invoices_create_loads(): void
    {
        $this->actingAs($this->buyer)->get('/procurement/invoices/create')->assertStatus(200);
    }

    // ===================== PROCUREMENT - RFQS =====================

    public function test_rfqs_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/procurement/rfqs')->assertStatus(200);
    }

    public function test_rfqs_create_loads(): void
    {
        $this->actingAs($this->buyer)->get('/procurement/rfqs/create')->assertStatus(200);
    }

    // ===================== PROCUREMENT - BUDGETS =====================

    public function test_budgets_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/procurement/budgets')->assertStatus(200);
    }

    public function test_budgets_create_loads(): void
    {
        $this->actingAs($this->admin)->get('/procurement/budgets/create')->assertStatus(200);
    }

    public function test_budgets_show_loads(): void
    {
        $budget = Budget::first();
        if ($budget) {
            $this->actingAs($this->admin)->get("/procurement/budgets/{$budget->id}")->assertStatus(200);
        } else {
            $this->assertTrue(true);
        }
    }

    // ===================== PROCUREMENT - SPEND ANALYSIS =====================

    public function test_spend_analysis_loads(): void
    {
        $this->actingAs($this->admin)->get('/procurement/spend-analysis')->assertStatus(200);
    }

    // ===================== VENDORS =====================

    public function test_vendors_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/vendors')->assertStatus(200);
    }

    public function test_vendors_create_loads(): void
    {
        $this->actingAs($this->admin)->get('/vendors/create')->assertStatus(200);
    }

    public function test_vendors_show_loads(): void
    {
        $vendor = Vendor::first();
        $this->actingAs($this->admin)->get("/vendors/{$vendor->id}")->assertStatus(200);
    }

    public function test_vendors_edit_loads(): void
    {
        $vendor = Vendor::first();
        $this->actingAs($this->admin)->get("/vendors/{$vendor->id}/edit")->assertStatus(200);
    }

    // ===================== CONTRACTS =====================

    public function test_contracts_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/contracts')->assertStatus(200);
    }

    public function test_contracts_create_loads(): void
    {
        $this->actingAs($this->admin)->get('/contracts/create')->assertStatus(200);
    }

    // ===================== INVENTORY =====================

    public function test_inventory_stock_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/inventory/stock')->assertStatus(200);
    }

    public function test_inventory_stock_adjust_form_loads(): void
    {
        $this->actingAs($this->admin)->get('/inventory/stock/adjust')->assertStatus(200);
    }

    public function test_inventory_warehouses_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/inventory/warehouses')->assertStatus(200);
    }

    public function test_inventory_warehouses_show_loads(): void
    {
        $wh = Warehouse::first();
        $this->actingAs($this->admin)->get("/inventory/warehouses/{$wh->id}")->assertStatus(200);
    }

    public function test_inventory_transfers_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/inventory/transfers')->assertStatus(200);
    }

    public function test_inventory_transfers_create_loads(): void
    {
        $this->actingAs($this->admin)->get('/inventory/transfers/create')->assertStatus(200);
    }

    public function test_inventory_reorders_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/inventory/reorders')->assertStatus(200);
    }

    public function test_inventory_shipments_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/inventory/shipments')->assertStatus(200);
    }

    // ===================== WAREHOUSE (WMS) =====================

    public function test_warehouse_orders_index_loads(): void
    {
        $this->actingAs($this->warehouseUser)->get('/warehouse/orders')->assertStatus(200);
    }

    public function test_warehouse_orders_create_loads(): void
    {
        $this->actingAs($this->warehouseUser)->get('/warehouse/orders/create')->assertStatus(200);
    }

    public function test_warehouse_receiving_loads(): void
    {
        $this->actingAs($this->warehouseUser)->get('/warehouse/receiving')->assertStatus(200);
    }

    public function test_warehouse_picking_loads(): void
    {
        $this->actingAs($this->warehouseUser)->get('/warehouse/picking')->assertStatus(200);
    }

    public function test_warehouse_packing_loads(): void
    {
        $this->actingAs($this->warehouseUser)->get('/warehouse/packing')->assertStatus(200);
    }

    public function test_warehouse_shipping_loads(): void
    {
        $this->actingAs($this->warehouseUser)->get('/warehouse/shipping')->assertStatus(200);
    }

    public function test_warehouse_scan_loads(): void
    {
        $this->actingAs($this->warehouseUser)->get('/warehouse/scan')->assertStatus(200);
    }

    public function test_warehouse_activities_loads(): void
    {
        $this->actingAs($this->warehouseUser)->get('/warehouse/activities')->assertStatus(200);
    }

    // ===================== FLEET =====================

    public function test_fleet_dashboard_loads(): void
    {
        $this->actingAs($this->admin)->get('/fleet')->assertStatus(200);
    }

    public function test_fleet_vehicles_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/fleet/vehicles')->assertStatus(200);
    }

    public function test_fleet_vehicles_create_loads(): void
    {
        $this->actingAs($this->admin)->get('/fleet/vehicles/create')->assertStatus(200);
    }

    public function test_fleet_vehicles_show_loads(): void
    {
        $vehicle = Vehicle::first();
        $this->actingAs($this->admin)->get("/fleet/vehicles/{$vehicle->id}")->assertStatus(200);
    }

    public function test_fleet_drivers_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/fleet/drivers')->assertStatus(200);
    }

    public function test_fleet_drivers_create_loads(): void
    {
        $this->actingAs($this->admin)->get('/fleet/drivers/create')->assertStatus(200);
    }

    public function test_fleet_drivers_show_loads(): void
    {
        $driver = Driver::first();
        $this->actingAs($this->admin)->get("/fleet/drivers/{$driver->id}")->assertStatus(200);
    }

    public function test_fleet_routes_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/fleet/routes')->assertStatus(200);
    }

    public function test_fleet_routes_create_loads(): void
    {
        $this->actingAs($this->admin)->get('/fleet/routes/create')->assertStatus(200);
    }

    public function test_fleet_trips_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/fleet/trips')->assertStatus(200);
    }

    public function test_fleet_trips_create_loads(): void
    {
        $this->actingAs($this->admin)->get('/fleet/trips/create')->assertStatus(200);
    }

    public function test_fleet_maintenance_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/fleet/maintenance')->assertStatus(200);
    }

    public function test_fleet_fuel_logs_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/fleet/fuel-logs')->assertStatus(200);
    }

    // ===================== SUPPLIER PORTAL =====================

    public function test_supplier_dashboard_loads(): void
    {
        $this->actingAs($this->supplierUser)->get('/supplier-portal')->assertStatus(200);
    }

    public function test_supplier_purchase_orders_loads(): void
    {
        $this->actingAs($this->supplierUser)->get('/supplier-portal/purchase-orders')->assertStatus(200);
    }

    public function test_supplier_invoices_loads(): void
    {
        $this->actingAs($this->supplierUser)->get('/supplier-portal/invoices')->assertStatus(200);
    }

    public function test_supplier_rfqs_loads(): void
    {
        $this->actingAs($this->supplierUser)->get('/supplier-portal/rfqs')->assertStatus(200);
    }

    public function test_supplier_performance_loads(): void
    {
        $this->actingAs($this->supplierUser)->get('/supplier-portal/performance')->assertStatus(200);
    }

    public function test_supplier_profile_loads(): void
    {
        $this->actingAs($this->supplierUser)->get('/supplier-portal/profile')->assertStatus(200);
    }

    // ===================== REPORTS =====================

    public function test_reports_index_loads(): void
    {
        $this->actingAs($this->admin)->get('/reports')->assertStatus(200);
    }

    public function test_reports_procurement_loads(): void
    {
        $this->actingAs($this->admin)->get('/reports/procurement')->assertStatus(200);
    }

    public function test_reports_inventory_loads(): void
    {
        $this->actingAs($this->admin)->get('/reports/inventory')->assertStatus(200);
    }

    public function test_reports_vendor_loads(): void
    {
        $this->actingAs($this->admin)->get('/reports/vendor')->assertStatus(200);
    }

    public function test_reports_fleet_loads(): void
    {
        $this->actingAs($this->admin)->get('/reports/fleet')->assertStatus(200);
    }

    // ===================== SEARCH =====================

    public function test_search_loads(): void
    {
        $this->actingAs($this->admin)->get('/search?q=test')->assertStatus(200);
    }
}
