<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\WarehouseOrder;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseOrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = WarehouseOrder::with(['warehouse', 'creator'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15);
        return view('warehouse.orders.index', compact('orders'));
    }

    public function create(): View
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::whereIn('status', ['sent', 'approved'])->with('vendor')->get();
        return view('warehouse.orders.create', compact('warehouses', 'products', 'purchaseOrders'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['warehouse_id' => 'required|exists:warehouses,id', 'type' => 'required|in:inbound,outbound,internal', 'purchase_order_id' => 'nullable|exists:purchase_orders,id', 'notes' => 'nullable|string', 'items' => 'required|array|min:1', 'items.*.product_id' => 'required|exists:products,id', 'items.*.expected_quantity' => 'required|numeric|min:1']);
        $order = WarehouseOrder::create($validated);
        foreach ($validated['items'] as $item) { $order->items()->create($item); }
        return redirect()->route('warehouse.orders.show', $order)->with('success', 'Warehouse order created.');
    }

    public function show(WarehouseOrder $order): View
    {
        $order->load(['warehouse', 'items.product', 'items.location', 'creator', 'activities.user']);
        return view('warehouse.orders.show', compact('order'));
    }

    public function edit(WarehouseOrder $order): View
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        return view('warehouse.orders.edit', compact('order', 'warehouses'));
    }

    public function update(Request $request, WarehouseOrder $order): RedirectResponse
    {
        $order->update($request->only(['notes', 'status']));
        return redirect()->route('warehouse.orders.show', $order)->with('success', 'Order updated.');
    }

    public function destroy(WarehouseOrder $order): RedirectResponse
    {
        $order->delete();
        return redirect()->route('warehouse.orders.index')->with('success', 'Order deleted.');
    }
}
