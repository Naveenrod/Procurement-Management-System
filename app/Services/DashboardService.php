<?php
namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\Invoice;
use App\Models\Vendor;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Models\WarehouseOrder;

class DashboardService
{
    public function getAdminStats(): array
    {
        return [
            'total_pos' => PurchaseOrder::count(),
            'pending_approvals' => PurchaseRequisition::where('status', 'pending_approval')->count()
                + PurchaseOrder::where('status', 'pending_approval')->count()
                + Invoice::where('status', 'pending_approval')->count(),
            'low_stock_items' => $this->getLowStockCount(),
            'active_vehicles' => Vehicle::where('status', 'in_use')->count(),
            'total_spend_month' => PurchaseOrder::whereNotIn('status', ['draft', 'cancelled'])
                ->whereYear('order_date', now()->year)
                ->whereMonth('order_date', now()->month)
                ->sum('total_amount'),
            'pending_invoices' => Invoice::whereIn('status', ['pending', 'pending_approval'])->count(),
            'active_vendors' => Vendor::where('status', 'approved')->count(),
            'active_trips' => Trip::where('status', 'in_progress')->count(),
        ];
    }

    public function getManagerStats(): array
    {
        return [
            'pending_approvals' => PurchaseRequisition::where('status', 'pending_approval')->count()
                + PurchaseOrder::where('status', 'pending_approval')->count(),
            'open_pos' => PurchaseOrder::whereIn('status', ['draft', 'pending_approval', 'approved', 'sent'])->count(),
            'pending_invoices' => Invoice::where('status', 'pending')->count(),
            'low_stock_items' => $this->getLowStockCount(),
            'total_spend_month' => PurchaseOrder::whereNotIn('status', ['draft', 'cancelled'])
                ->whereYear('order_date', now()->year)
                ->whereMonth('order_date', now()->month)
                ->sum('total_amount'),
        ];
    }

    public function getBuyerStats(): array
    {
        $userId = auth()->id();
        return [
            'my_requisitions' => PurchaseRequisition::where('requested_by', $userId)->count(),
            'draft_requisitions' => PurchaseRequisition::where('requested_by', $userId)->where('status', 'draft')->count(),
            'approved_requisitions' => PurchaseRequisition::where('requested_by', $userId)->where('status', 'approved')->count(),
            'open_pos' => PurchaseOrder::where('created_by', $userId)->whereIn('status', ['draft', 'pending_approval', 'approved'])->count(),
        ];
    }

    public function getWarehouseStats(): array
    {
        return [
            'orders_to_process' => WarehouseOrder::where('status', 'pending')->count(),
            'orders_to_receive' => WarehouseOrder::where('type', 'inbound')->where('status', 'pending')->count(),
            'orders_to_pick' => WarehouseOrder::where('type', 'outbound')->where('status', 'putaway')->count(),
            'low_stock_items' => $this->getLowStockCount(),
        ];
    }

    public function getRecentActivity(int $limit = 10): array
    {
        $requisitions = PurchaseRequisition::with('creator')
            ->latest()->limit($limit)->get()
            ->map(fn($r) => ['type' => 'requisition', 'description' => "Requisition {$r->requisition_number} created", 'user' => $r->creator?->name ?? 'System', 'time' => $r->created_at, 'status' => $r->status]);

        $pos = PurchaseOrder::with('creator')
            ->latest()->limit($limit)->get()
            ->map(fn($po) => ['type' => 'purchase_order', 'description' => "PO {$po->po_number} created", 'user' => $po->creator?->name ?? 'System', 'time' => $po->created_at, 'status' => $po->status]);

        return $requisitions->concat($pos)->sortByDesc('time')->take($limit)->values()->toArray();
    }

    public function getSpendByMonth(int $months = 6): array
    {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $spend = PurchaseOrder::whereYear('order_date', $date->year)
                ->whereMonth('order_date', $date->month)
                ->whereNotIn('status', ['draft', 'cancelled'])
                ->sum('total_amount');
            $data[] = ['month' => $date->format('M Y'), 'total' => (float) $spend];
        }
        return $data;
    }

    private function getLowStockCount(): int
    {
        return Inventory::join('products', 'inventory.product_id', '=', 'products.id')
            ->whereColumn('inventory.quantity_on_hand', '<=', 'products.reorder_point')
            ->where('products.reorder_point', '>', 0)
            ->count();
    }
}
