<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\WarehouseOrderController;
use App\Http\Controllers\Api\BarcodeScanController;
use App\Http\Controllers\Api\ShipmentController;
use App\Http\Controllers\Api\ReportController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());

    Route::apiResource('products', ProductController::class);
    Route::apiResource('vendors', VendorController::class)->only(['index', 'show']);
    Route::apiResource('purchase-orders', PurchaseOrderController::class)->only(['index', 'show']);
    Route::apiResource('inventory', InventoryController::class)->only(['index', 'show']);
    Route::post('inventory/adjust', [InventoryController::class, 'adjust']);

    Route::get('warehouse/orders', [WarehouseOrderController::class, 'index']);
    Route::post('warehouse/scan', [BarcodeScanController::class, 'scan']);

    Route::get('shipments/{shipment}', [ShipmentController::class, 'show']);
    Route::get('shipments/{shipment}/track', [ShipmentController::class, 'track']);

    Route::get('reports/spend', [ReportController::class, 'spend']);
    Route::get('reports/inventory-value', [ReportController::class, 'inventoryValue']);
});
