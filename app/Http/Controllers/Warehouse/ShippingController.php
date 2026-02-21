<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\WarehouseOrder;
use App\Services\WarehouseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingController extends Controller
{
    public function __construct(private readonly WarehouseService $warehouseService) {}

    public function index(): View
    {
        $orders = WarehouseOrder::whereIn('status', ['packing', 'shipped'])->with(['warehouse', 'items'])->latest()->get();
        return view('warehouse.shipping.index', compact('orders'));
    }

    public function ship(Request $request, WarehouseOrder $order): RedirectResponse
    {
        $this->warehouseService->processShipping($order, $request->input('tracking_number'));
        return redirect()->route('warehouse.shipping.index')->with('success', 'Order shipped.');
    }
}
