<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\WarehouseOrder;
use App\Services\WarehouseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReceivingController extends Controller
{
    public function __construct(private readonly WarehouseService $warehouseService) {}

    public function index(): View
    {
        $orders = WarehouseOrder::where('type', 'inbound')->where('status', 'pending')->with(['warehouse', 'items.product'])->get();
        return view('warehouse.receiving.index', compact('orders'));
    }

    public function receive(Request $request, WarehouseOrder $order): RedirectResponse
    {
        $order->load('items');
        $items = $order->items->map(fn($item) => [
            'warehouse_order_item_id' => $item->id,
            'received_quantity'       => $item->expected_quantity,
            'condition'               => 'good',
        ])->all();
        $this->warehouseService->processReceiving($order, $items);
        return redirect()->route('warehouse.receiving.index')->with('success', 'Order received.');
    }
}
