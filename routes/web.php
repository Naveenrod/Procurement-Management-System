<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Procurement\PurchaseRequisitionController;
use App\Http\Controllers\Procurement\RfqController;
use App\Http\Controllers\Procurement\PurchaseOrderController;
use App\Http\Controllers\Procurement\GoodsReceiptController;
use App\Http\Controllers\Procurement\InvoiceController;
use App\Http\Controllers\Procurement\BudgetController;
use App\Http\Controllers\Procurement\SpendAnalysisController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Vendor\VendorContactController;
use App\Http\Controllers\Vendor\VendorDocumentController;
use App\Http\Controllers\Vendor\ContractController;
use App\Http\Controllers\Vendor\VendorPerformanceController;
use App\Http\Controllers\Inventory\WarehouseController;
use App\Http\Controllers\Inventory\WarehouseLocationController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\InventoryTransferController;
use App\Http\Controllers\Inventory\CycleCountController;
use App\Http\Controllers\Inventory\ShipmentController;
use App\Http\Controllers\Inventory\ReorderController;
use App\Http\Controllers\Warehouse\WarehouseOrderController;
use App\Http\Controllers\Warehouse\ReceivingController;
use App\Http\Controllers\Warehouse\PickingController;
use App\Http\Controllers\Warehouse\PackingController;
use App\Http\Controllers\Warehouse\ShippingController;
use App\Http\Controllers\Warehouse\BarcodeScanController;
use App\Http\Controllers\Warehouse\WarehouseActivityController;
use App\Http\Controllers\Fleet\VehicleController;
use App\Http\Controllers\Fleet\DriverController;
use App\Http\Controllers\Fleet\RouteController;
use App\Http\Controllers\Fleet\TripController;
use App\Http\Controllers\Fleet\MaintenanceController;
use App\Http\Controllers\Fleet\FuelLogController;
use App\Http\Controllers\Fleet\FleetDashboardController;
use App\Http\Controllers\SupplierPortal\SupplierDashboardController;
use App\Http\Controllers\SupplierPortal\SupplierPurchaseOrderController;
use App\Http\Controllers\SupplierPortal\SupplierDeliveryController;
use App\Http\Controllers\SupplierPortal\SupplierInvoiceController;
use App\Http\Controllers\SupplierPortal\SupplierRfqController;
use App\Http\Controllers\SupplierPortal\SupplierPerformanceController;
use App\Http\Controllers\SupplierPortal\SupplierProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;

// Welcome page
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Quick login route (for testing)
Route::post('/quick-login', [AuthenticatedSessionController::class, 'quickLogin'])
    ->name('quick-login')
    ->middleware('guest');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mark notification as read
    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        $url = $notification?->data['url'] ?? null;
        $notification?->markAsRead();
        return $url ? redirect($url) : back();
    })->name('notifications.read');
    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read-all');

    // Procurement Module
    Route::middleware(['role:admin|manager|buyer'])->prefix('procurement')->name('procurement.')->group(function () {
        // Purchase Requisitions
        Route::resource('requisitions', PurchaseRequisitionController::class);
        Route::post('requisitions/{requisition}/submit', [PurchaseRequisitionController::class, 'submit'])->name('requisitions.submit');
        Route::post('requisitions/{requisition}/approve', [PurchaseRequisitionController::class, 'approve'])->name('requisitions.approve');
        Route::post('requisitions/{requisition}/reject', [PurchaseRequisitionController::class, 'reject'])->name('requisitions.reject');
        Route::post('requisitions/{requisition}/convert-to-po', [PurchaseRequisitionController::class, 'convertToPo'])->name('requisitions.convert-to-po');

        // RFQs
        Route::resource('rfqs', RfqController::class);
        Route::post('rfqs/{rfq}/publish', [RfqController::class, 'publish'])->name('rfqs.publish');
        Route::post('rfqs/{rfq}/close', [RfqController::class, 'close'])->name('rfqs.close');
        Route::post('rfqs/{rfq}/award', [RfqController::class, 'award'])->name('rfqs.award');

        // Purchase Orders
        Route::resource('purchase-orders', PurchaseOrderController::class);
        Route::post('purchase-orders/{purchase_order}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
        Route::post('purchase-orders/{purchase_order}/send', [PurchaseOrderController::class, 'send'])->name('purchase-orders.send');
        Route::post('purchase-orders/{purchase_order}/reject', [PurchaseOrderController::class, 'reject'])->name('purchase-orders.reject')->middleware('role:admin|manager');

        // Goods Receipts
        Route::resource('goods-receipts', GoodsReceiptController::class);

        // Invoices
        Route::resource('invoices', InvoiceController::class);
        Route::post('invoices/{invoice}/match', [InvoiceController::class, 'threeWayMatch'])->name('invoices.match');
        Route::post('invoices/{invoice}/approve', [InvoiceController::class, 'approve'])->name('invoices.approve');

        // Budgets
        Route::resource('budgets', BudgetController::class);

        // Spend Analysis
        Route::get('spend-analysis', [SpendAnalysisController::class, 'index'])->name('spend-analysis');
    });

    // Vendor Management Module
    Route::middleware(['role:admin|manager'])->group(function () {
        Route::resource('vendors', VendorController::class);
        Route::post('vendors/{vendor}/approve', [VendorController::class, 'approve'])->name('vendors.approve');
        Route::post('vendors/{vendor}/suspend', [VendorController::class, 'suspend'])->name('vendors.suspend');

        // Vendor nested resources
        Route::post('vendors/{vendor}/contacts', [VendorContactController::class, 'store'])->name('vendors.contacts.store');
        Route::put('vendors/{vendor}/contacts/{contact}', [VendorContactController::class, 'update'])->name('vendors.contacts.update');
        Route::delete('vendors/{vendor}/contacts/{contact}', [VendorContactController::class, 'destroy'])->name('vendors.contacts.destroy');

        Route::post('vendors/{vendor}/documents', [VendorDocumentController::class, 'store'])->name('vendors.documents.store');
        Route::delete('vendors/{vendor}/documents/{document}', [VendorDocumentController::class, 'destroy'])->name('vendors.documents.destroy');
        Route::get('vendors/{vendor}/documents/{document}/download', [VendorDocumentController::class, 'download'])->name('vendors.documents.download');

        // Contracts
        Route::resource('contracts', ContractController::class);
        Route::post('contracts/{contract}/approve', [ContractController::class, 'approve'])->name('contracts.approve');
        Route::post('contracts/{contract}/terminate', [ContractController::class, 'terminate'])->name('contracts.terminate');

        // Vendor Performance
        Route::get('vendors/{vendor}/performance', [VendorPerformanceController::class, 'index'])->name('vendors.performance.index');
        Route::get('vendors/{vendor}/performance/create', [VendorPerformanceController::class, 'create'])->name('vendors.performance.create');
        Route::post('vendors/{vendor}/performance', [VendorPerformanceController::class, 'store'])->name('vendors.performance.store');
    });

    // Inventory Module
    Route::middleware(['role:admin|manager|buyer|warehouse_worker'])->prefix('inventory')->name('inventory.')->group(function () {
        Route::resource('warehouses', WarehouseController::class);

        // Warehouse Locations (nested)
        Route::get('warehouses/{warehouse}/locations', [WarehouseLocationController::class, 'index'])->name('warehouses.locations.index');
        Route::post('warehouses/{warehouse}/locations', [WarehouseLocationController::class, 'store'])->name('warehouses.locations.store');
        Route::put('warehouses/{warehouse}/locations/{location}', [WarehouseLocationController::class, 'update'])->name('warehouses.locations.update');
        Route::delete('warehouses/{warehouse}/locations/{location}', [WarehouseLocationController::class, 'destroy'])->name('warehouses.locations.destroy');

        // Stock
        Route::get('stock', [InventoryController::class, 'index'])->name('stock.index');
        Route::get('stock/adjust', [InventoryController::class, 'adjustForm'])->name('stock.adjust-form');
        Route::post('stock/adjust', [InventoryController::class, 'adjust'])->name('stock.adjust');

        // Transfers
        Route::resource('transfers', InventoryTransferController::class);
        Route::post('transfers/{transfer}/approve', [InventoryTransferController::class, 'approve'])->name('transfers.approve');
        Route::post('transfers/{transfer}/ship', [InventoryTransferController::class, 'ship'])->name('transfers.ship');
        Route::post('transfers/{transfer}/receive', [InventoryTransferController::class, 'receive'])->name('transfers.receive');

        // Cycle Counts
        Route::resource('cycle-counts', CycleCountController::class);
        Route::get('cycle-counts/{cycle_count}/count', [CycleCountController::class, 'countForm'])->name('cycle-counts.count-form');
        Route::post('cycle-counts/{cycle_count}/count', [CycleCountController::class, 'count'])->name('cycle-counts.count');
        Route::post('cycle-counts/{cycle_count}/reconcile', [CycleCountController::class, 'reconcile'])->name('cycle-counts.reconcile');

        // Shipments
        Route::resource('shipments', ShipmentController::class);

        // Reorder Alerts
        Route::get('reorders', [ReorderController::class, 'index'])->name('reorders.index');
    });

    // Warehouse Management (WMS) Module
    Route::middleware(['role:admin|manager|warehouse_worker'])->prefix('warehouse')->name('warehouse.')->group(function () {
        Route::resource('orders', WarehouseOrderController::class);

        Route::get('receiving', [ReceivingController::class, 'index'])->name('receiving.index');
        Route::post('receiving/{order}/process', [ReceivingController::class, 'receive'])->name('receiving.process');

        Route::get('picking', [PickingController::class, 'index'])->name('picking.index');
        Route::post('picking/{order}/process', [PickingController::class, 'pick'])->name('picking.process');

        Route::get('packing', [PackingController::class, 'index'])->name('packing.index');
        Route::post('packing/{order}/process', [PackingController::class, 'pack'])->name('packing.process');

        Route::get('shipping', [ShippingController::class, 'index'])->name('shipping.index');
        Route::post('shipping/{order}/process', [ShippingController::class, 'ship'])->name('shipping.process');

        Route::get('scan', [BarcodeScanController::class, 'index'])->name('scan.index');
        Route::post('scan', [BarcodeScanController::class, 'scan'])->name('scan.process');

        Route::get('activities', [WarehouseActivityController::class, 'index'])->name('activities.index');
    });

    // Fleet Management Module
    Route::middleware(['role:admin|manager'])->prefix('fleet')->name('fleet.')->group(function () {
        Route::get('/', [FleetDashboardController::class, 'index'])->name('dashboard');
        Route::resource('vehicles', VehicleController::class);
        Route::resource('drivers', DriverController::class);
        Route::resource('routes', RouteController::class);
        Route::resource('trips', TripController::class);
        Route::post('trips/{trip}/start', [TripController::class, 'start'])->name('trips.start');
        Route::post('trips/{trip}/complete', [TripController::class, 'complete'])->name('trips.complete');
        Route::resource('maintenance', MaintenanceController::class);
        Route::resource('fuel-logs', FuelLogController::class);
        Route::get('fuel-report', [FuelLogController::class, 'report'])->name('fuel.report');
    });

    // Supplier Portal
    Route::middleware(['role:supplier'])->prefix('supplier-portal')->name('supplier.')->group(function () {
        Route::get('/', [SupplierDashboardController::class, 'index'])->name('dashboard');

        Route::get('purchase-orders', [SupplierPurchaseOrderController::class, 'index'])->name('purchase-orders.index');
        Route::get('purchase-orders/{purchase_order}', [SupplierPurchaseOrderController::class, 'show'])->name('purchase-orders.show');
        Route::post('purchase-orders/{purchase_order}/acknowledge', [SupplierPurchaseOrderController::class, 'acknowledge'])->name('purchase-orders.acknowledge');
        Route::post('purchase-orders/{purchase_order}/update-delivery', [SupplierDeliveryController::class, 'update'])->name('delivery.update');

        Route::get('invoices', [SupplierInvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/create/{purchase_order}', [SupplierInvoiceController::class, 'create'])->name('invoices.create');
        Route::post('invoices', [SupplierInvoiceController::class, 'store'])->name('invoices.store');
        Route::get('invoices/{invoice}', [SupplierInvoiceController::class, 'show'])->name('invoices.show');

        Route::get('rfqs', [SupplierRfqController::class, 'index'])->name('rfqs.index');
        Route::get('rfqs/{rfq}', [SupplierRfqController::class, 'show'])->name('rfqs.show');
        Route::post('rfqs/{rfq}/respond', [SupplierRfqController::class, 'respond'])->name('rfqs.respond');

        Route::get('performance', [SupplierPerformanceController::class, 'index'])->name('performance');
        Route::get('profile', [SupplierProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [SupplierProfileController::class, 'update'])->name('profile.update');
    });

    // Reports
    Route::middleware(['role:admin|manager'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('procurement', [ReportController::class, 'procurement'])->name('procurement');
        Route::get('inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('vendor', [ReportController::class, 'vendor'])->name('vendor');
        Route::get('fleet', [ReportController::class, 'fleet'])->name('fleet');
    });

    // Global Search
    Route::get('/search', function (\Illuminate\Http\Request $request) {
        $query = $request->get('q', '');
        if (strlen($query) < 2) return back();

        $results = [
            'products' => \App\Models\Product::where('name', 'like', "%{$query}%")->orWhere('sku', 'like', "%{$query}%")->take(5)->get(),
            'vendors' => \App\Models\Vendor::where('name', 'like', "%{$query}%")->orWhere('code', 'like', "%{$query}%")->take(5)->get(),
            'purchase_orders' => \App\Models\PurchaseOrder::where('po_number', 'like', "%{$query}%")->take(5)->get(),
            'invoices' => \App\Models\Invoice::where('invoice_number', 'like', "%{$query}%")->take(5)->get(),
        ];

        return view('search.results', compact('results', 'query'));
    })->name('search');
});

require __DIR__.'/auth.php';
