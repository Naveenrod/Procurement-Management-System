<?php
namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\Invoice;
use App\Models\Vendor;
use App\Models\Vehicle;
use App\Models\Inventory;
use App\Models\Trip;
use App\Models\FuelLog;
use App\Models\MaintenanceRecord;
use App\Models\VendorPerformanceScore;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getProcurementReport(array $filters = []): array
    {
        $from = $filters['from'] ?? now()->startOfYear()->toDateString();
        $to = $filters['to'] ?? now()->toDateString();

        $pos = PurchaseOrder::with('vendor')->whereBetween('order_date', [$from, $to])->get();

        return [
            'total_pos' => $pos->count(),
            'total_spend' => $pos->whereNotIn('status', ['draft', 'cancelled'])->sum('total_amount'),
            'by_status' => $pos->groupBy('status')->map->count(),
        ];
    }

    public function getInventoryReport(array $filters = []): array
    {
        $inventory = Inventory::with(['product', 'warehouse'])->get();

        $byWarehouse = DB::table('inventory')
            ->join('warehouses', 'inventory.warehouse_id', '=', 'warehouses.id')
            ->join('products', 'inventory.product_id', '=', 'products.id')
            ->groupBy('warehouses.id', 'warehouses.name')
            ->select(
                'warehouses.name as warehouse_name',
                DB::raw('SUM(inventory.quantity_on_hand) as total_quantity'),
                DB::raw('SUM(inventory.quantity_on_hand * products.unit_price) as total_value')
            )
            ->orderByDesc('total_value')
            ->get();

        $lowStockItems = DB::table('inventory')
            ->join('products', 'inventory.product_id', '=', 'products.id')
            ->join('warehouses', 'inventory.warehouse_id', '=', 'warehouses.id')
            ->where('products.reorder_point', '>', 0)
            ->whereColumn('inventory.quantity_on_hand', '<=', 'products.reorder_point')
            ->select(
                'products.name as product_name',
                'products.sku',
                'warehouses.name as warehouse_name',
                'inventory.quantity_on_hand',
                'products.reorder_point'
            )
            ->orderBy('inventory.quantity_on_hand')
            ->limit(10)
            ->get();

        $totalSkus = DB::table('inventory')
            ->distinct('product_id')
            ->count('product_id');

        return [
            'total_items' => $inventory->count(),
            'total_value' => $inventory->sum(fn($i) => $i->quantity_on_hand * ($i->product->unit_price ?? 0)),
            'low_stock' => $inventory->filter(fn($i) => $i->quantity_on_hand <= ($i->product->reorder_point ?? 0))->count(),
            'total_skus' => $totalSkus,
            'by_warehouse' => $byWarehouse,
            'low_stock_items' => $lowStockItems,
        ];
    }

    public function getVendorReport(array $filters = []): array
    {
        $byStatus = Vendor::withoutTrashed()
            ->groupBy('status')
            ->select('status', DB::raw('count(*) as count'))
            ->get();

        $performanceSummary = DB::table('vendor_performance_scores')
            ->join('vendors', 'vendor_performance_scores.vendor_id', '=', 'vendors.id')
            ->groupBy('vendors.id', 'vendors.name')
            ->select(
                'vendors.name as vendor_name',
                DB::raw('AVG(vendor_performance_scores.delivery_score) as avg_delivery'),
                DB::raw('AVG(vendor_performance_scores.quality_score) as avg_quality'),
                DB::raw('AVG(vendor_performance_scores.price_score) as avg_price'),
                DB::raw('AVG(vendor_performance_scores.overall_score) as avg_overall')
            )
            ->orderByDesc('avg_overall')
            ->limit(10)
            ->get();

        $avgPerformance = VendorPerformanceScore::avg('overall_score');

        return [
            'total_vendors' => Vendor::count(),
            'approved_vendors' => Vendor::where('status', 'approved')->count(),
            'active_vendors' => Vendor::where('status', 'approved')->count(),
            'by_status' => $byStatus,
            'performance_summary' => $performanceSummary,
            'avg_performance' => round($avgPerformance ?? 0),
        ];
    }

    public function getFleetReport(array $filters = []): array
    {
        $from = $filters['from'] ?? now()->startOfYear()->toDateString();
        $to = $filters['to'] ?? now()->toDateString();

        $totalVehicles = Vehicle::count();
        $maintenanceCost = MaintenanceRecord::whereBetween('scheduled_date', [$from, $to])->sum('cost');

        $byVehicle = DB::table('vehicles')
            ->leftJoin('trips', function ($join) use ($from, $to) {
                $join->on('vehicles.id', '=', 'trips.vehicle_id')
                    ->whereBetween('trips.scheduled_at', [$from, $to]);
            })
            ->leftJoin('fuel_logs', function ($join) use ($from, $to) {
                $join->on('vehicles.id', '=', 'fuel_logs.vehicle_id')
                    ->whereBetween('fuel_logs.filled_at', [$from, $to]);
            })
            ->groupBy('vehicles.id', 'vehicles.registration_number')
            ->select(
                'vehicles.registration_number',
                DB::raw('COUNT(DISTINCT trips.id) as trip_count'),
                DB::raw('COALESCE(SUM(fuel_logs.total_cost), 0) as fuel_cost')
            )
            ->orderByDesc('trip_count')
            ->get();

        // Fuel cost by month for trend chart
        $fuelByMonth = collect();
        $months = 6;
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $cost = FuelLog::whereYear('filled_at', $date->year)
                ->whereMonth('filled_at', $date->month)
                ->sum('total_cost');
            $fuelByMonth->push(['month' => $date->format('M Y'), 'total' => (float) $cost]);
        }

        return [
            'total_vehicles' => $totalVehicles,
            'total_trips' => Trip::whereBetween('scheduled_at', [$from, $to])->count(),
            'completed_trips' => Trip::where('status', 'completed')->whereBetween('scheduled_at', [$from, $to])->count(),
            'total_fuel_cost' => FuelLog::whereBetween('filled_at', [$from, $to])->sum('total_cost'),
            'maintenance_cost' => $maintenanceCost,
            'by_vehicle' => $byVehicle,
            'fuel_by_month' => $fuelByMonth,
        ];
    }

    public function getSpendByCategory(): \Illuminate\Support\Collection
    {
        return DB::table('purchase_order_items')
            ->join('products', 'purchase_order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('purchase_orders', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
            ->whereNotIn('purchase_orders.status', ['draft', 'cancelled'])
            ->groupBy('categories.id', 'categories.name')
            ->select('categories.name', DB::raw('SUM(purchase_order_items.total_price) as total'))
            ->orderByDesc('total')
            ->get();
    }

    public function getSpendByVendor(): \Illuminate\Support\Collection
    {
        return PurchaseOrder::join('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
            ->whereNotIn('purchase_orders.status', ['draft', 'cancelled'])
            ->groupBy('vendors.id', 'vendors.name')
            ->select('vendors.name as vendor_name', DB::raw('SUM(purchase_orders.total_amount) as total'), DB::raw('COUNT(*) as count'))
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    public function getPoStatusDistribution(): \Illuminate\Support\Collection
    {
        return PurchaseOrder::groupBy('status')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->orderByDesc('count')
            ->toBase()
            ->get();
    }

    public function getSpendByMonth(int $months = 6): \Illuminate\Support\Collection
    {
        $results = collect();
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $total = PurchaseOrder::whereYear('order_date', $date->year)
                ->whereMonth('order_date', $date->month)
                ->whereNotIn('status', ['draft', 'cancelled'])
                ->sum('total_amount');
            $results->push(['month' => $date->format('M Y'), 'total' => (float) $total]);
        }
        return $results;
    }
}
