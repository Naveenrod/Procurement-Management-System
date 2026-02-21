<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\PurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    public function index(): View
    {
        $shipments = Shipment::with('purchaseOrder.vendor')->latest()->paginate(15);
        return view('inventory.shipments.index', compact('shipments'));
    }

    public function create(): View
    {
        $purchaseOrders = PurchaseOrder::whereIn('status', ['sent', 'approved', 'acknowledged'])->with('vendor')->get();
        return view('inventory.shipments.create', compact('purchaseOrders'));
    }

    public function store(Request $request): RedirectResponse
    {
        $shipment = Shipment::create($request->validate(['purchase_order_id' => 'required|exists:purchase_orders,id', 'carrier' => 'nullable|string', 'estimated_arrival' => 'nullable|date', 'notes' => 'nullable|string']));
        return redirect()->route('inventory.shipments.show', $shipment)->with('success', 'Shipment created.');
    }

    public function show(Shipment $shipment): View
    {
        $shipment->load('purchaseOrder.vendor');
        return view('inventory.shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment): View { return view('inventory.shipments.edit', compact('shipment')); }

    public function update(Request $request, Shipment $shipment): RedirectResponse
    {
        $shipment->update($request->only(['carrier', 'status', 'estimated_arrival', 'notes']));
        return redirect()->route('inventory.shipments.show', $shipment)->with('success', 'Shipment updated.');
    }

    public function destroy(Shipment $shipment): RedirectResponse
    {
        $shipment->delete();
        return redirect()->route('inventory.shipments.index')->with('success', 'Shipment deleted.');
    }
}
