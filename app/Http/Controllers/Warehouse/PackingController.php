<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\WarehouseOrder;
use App\Services\WarehouseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackingController extends Controller
{
    public function __construct(private readonly WarehouseService $warehouseService) {}

    public function index(): View
    {
        $orders = WarehouseOrder::where('status', 'picking')->with(['warehouse', 'items.product'])->get();
        return view('warehouse.packing.index', compact('orders'));
    }

    public function pack(Request $request, WarehouseOrder $order): RedirectResponse
    {
        $this->warehouseService->processPacking($order, $request->all());
        return redirect()->route('warehouse.packing.index')->with('success', 'Order packed.');
    }
}
