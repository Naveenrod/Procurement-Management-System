<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\WarehouseOrder;
use App\Services\WarehouseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PickingController extends Controller
{
    public function __construct(private readonly WarehouseService $warehouseService) {}

    public function index(): View
    {
        $orders = WarehouseOrder::where('type', 'outbound')->whereIn('status', ['putaway', 'picking'])->with(['warehouse', 'items.product', 'items.location'])->get();
        return view('warehouse.picking.index', compact('orders'));
    }

    public function pick(Request $request, WarehouseOrder $order): RedirectResponse
    {
        $this->warehouseService->processPicking($order, $request->all());
        return redirect()->route('warehouse.picking.index')->with('success', 'Pick list processed.');
    }
}
