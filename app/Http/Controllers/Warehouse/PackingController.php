<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\WarehouseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class PackingController extends Controller
{
    public function index(): View
    {
        $orders = WarehouseOrder::where('status', 'picking')
            ->with(['warehouse', 'items.product'])
            ->get();
        return view('warehouse.packing.index', compact('orders'));
    }

    public function pack(Request $request, WarehouseOrder $order): RedirectResponse
    {
        $request->validate([
            'items'           => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:warehouse_order_items,id',
            'items.*.sku'     => 'required|string',
        ]);

        $order->load('items.product');
        $skuErrors = [];

        foreach ($request->items as $data) {
            $item = $order->items->firstWhere('id', $data['item_id']);
            if (!$item) continue;

            if (strtoupper(trim($data['sku'])) !== strtoupper(trim($item->product->sku))) {
                $skuErrors[] = "SKU mismatch for \"{$item->product->name}\": expected {$item->product->sku}, got {$data['sku']}.";
            }
        }

        if (!empty($skuErrors)) {
            return back()->withInput()->withErrors(['sku' => $skuErrors]);
        }

        DB::transaction(function () use ($order) {
            $order->items()->where('status', 'picked')->update(['status' => 'packed']);
            $order->update(['status' => 'packing']);
        });

        return redirect()->route('warehouse.packing.index')
            ->with('success', 'Order ' . $order->order_number . ' packed and ready for shipping.');
    }
}
