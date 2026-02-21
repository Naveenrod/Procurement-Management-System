<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\WarehouseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PickingController extends Controller
{
    public function index(): View
    {
        $orders = WarehouseOrder::where('type', 'outbound')
            ->where('status', 'pending')
            ->with(['warehouse', 'items.product', 'items.location'])
            ->get();
        return view('warehouse.picking.index', compact('orders'));
    }

    public function pick(Request $request, WarehouseOrder $order): RedirectResponse
    {
        $request->validate([
            'items'                   => 'required|array|min:1',
            'items.*.item_id'         => 'required|exists:warehouse_order_items,id',
            'items.*.sku'             => 'required|string',
            'items.*.picked_quantity' => 'required|numeric|min:0',
        ]);

        $order->load('items.product');
        $skuErrors = [];

        foreach ($request->items as $data) {
            $item = $order->items->firstWhere('id', $data['item_id']);
            if (!$item) continue;

            if (strtoupper(trim($data['sku'])) !== strtoupper(trim($item->product->sku))) {
                $skuErrors[] = "SKU mismatch for \"{$item->product->name}\": expected {$item->product->sku}, got {$data['sku']}.";
                continue;
            }

            $qty = (float) $data['picked_quantity'];
            $item->update([
                'picked_quantity' => $qty,
                'status'          => $qty > 0 ? 'picked' : 'pending',
                'picked_at'       => $qty > 0 ? now() : null,
            ]);
        }

        if (!empty($skuErrors)) {
            return back()
                ->withInput()
                ->withErrors(['sku' => $skuErrors]);
        }

        if ($order->items()->where('status', '!=', 'picked')->doesntExist()) {
            $order->update(['status' => 'picking']);
            return redirect()->route('warehouse.picking.index')->with('success', 'Pick list confirmed — order moved to packing.');
        }

        return redirect()->route('warehouse.picking.index')->with('success', 'Partial pick saved.');
    }
}
