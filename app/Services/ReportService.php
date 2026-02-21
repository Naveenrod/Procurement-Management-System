<?php
namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\Invoice;
use App\Models\Vendor;
use App\Models\Inventory;
use App\Models\Trip;
use App\Models\FuelLog;
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

        return [
            'total_items' => $inventory->count(),
            'total_value' => $inventory->sum(fn($i) => $i->quantity_on_hand * ($i->product->unit_price ?? 0)),
            'low_stock' => $inventory->filter(fn($i) => $i->quantity_on_hand <= ($i->product->reorder_point ?? 0))->count(),
        ];
    }

    public function getVendorReport(array $filters = []): array
    {
        return [
            'total_vendors' => Vendor::count(),
            'approved_vendors' => Vendor::where('status', 'approved')->count(),
            'by_status' => Vendor::groupBy('status')->select('status', DB::raw('count(*) as count'))->get(),
        ];
    }

    public function getFleetReport(array $filters = []): array
    {
        $from = $filters['from'] ?? now()->startOfMonth()->toDateString();
        $to = $filters['to'] ?? now()->toDateString();

        return [
            'total_trips' => Trip::whereBetween('scheduled_at', [$from, $to])->count(),
            'completed_trips' => Trip::where('status', 'completed')->whereBetween('scheduled_at', [$from, $to])->count(),
            'total_fuel_cost' => FuelLog::whereBetween('filled_at', [$from, $to])->sum('total_cost'),
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
            ->select('vendors.name', DB::raw('SUM(purchase_orders.total_amount) as total'), DB::raw('COUNT(*) as po_count'))
            ->orderByDesc('total')
            ->limit(10)
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
