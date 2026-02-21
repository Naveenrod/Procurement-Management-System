<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransfer;
use App\Models\Warehouse;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryTransferController extends Controller
{
    public function __construct(private readonly InventoryService $inventoryService) {}

    public function index(): View
    {
        $transfers = InventoryTransfer::with(['fromWarehouse', 'toWarehouse', 'requester'])->latest()->paginate(15);
        return view('inventory.transfers.index', compact('transfers'));
    }

    public function create(): View
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::orderBy('name')->get();
        return view('inventory.transfers.create', compact('warehouses', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['from_warehouse_id' => 'required|exists:warehouses,id|different:to_warehouse_id', 'to_warehouse_id' => 'required|exists:warehouses,id', 'notes' => 'nullable|string', 'items' => 'required|array|min:1', 'items.*.product_id' => 'required|exists:products,id', 'items.*.quantity_requested' => 'required|numeric|min:0.01']);
        $transfer = InventoryTransfer::create($validated);
        foreach ($validated['items'] as $item) { $transfer->items()->create($item); }
        return redirect()->route('inventory.transfers.show', $transfer)->with('success', 'Transfer created.');
    }

    public function show(InventoryTransfer $transfer): View
    {
        $transfer->load(['fromWarehouse', 'toWarehouse', 'items.product', 'requester', 'approver']);
        return view('inventory.transfers.show', compact('transfer'));
    }

    public function edit(InventoryTransfer $transfer): View
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        return view('inventory.transfers.edit', compact('transfer', 'warehouses'));
    }

    public function update(Request $request, InventoryTransfer $transfer): RedirectResponse
    {
        $transfer->update($request->only(['notes']));
        return redirect()->route('inventory.transfers.show', $transfer)->with('success', 'Transfer updated.');
    }

    public function destroy(InventoryTransfer $transfer): RedirectResponse
    {
        $transfer->delete();
        return redirect()->route('inventory.transfers.index')->with('success', 'Transfer deleted.');
    }

    public function approve(InventoryTransfer $transfer): RedirectResponse
    {
        $transfer->update(['status' => 'approved', 'approved_by' => auth()->id()]);
        return redirect()->route('inventory.transfers.show', $transfer)->with('success', 'Transfer approved.');
    }

    public function ship(InventoryTransfer $transfer): RedirectResponse
    {
        $this->inventoryService->processTransferShipment($transfer);
        return redirect()->route('inventory.transfers.show', $transfer)->with('success', 'Transfer shipped.');
    }

    public function receive(InventoryTransfer $transfer): RedirectResponse
    {
        $this->inventoryService->processTransferReceipt($transfer);
        return redirect()->route('inventory.transfers.show', $transfer)->with('success', 'Transfer received.');
    }
}
