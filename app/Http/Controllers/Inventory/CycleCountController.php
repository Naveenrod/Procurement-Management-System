<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\CycleCount;
use App\Models\Warehouse;
use App\Models\Inventory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CycleCountController extends Controller
{
    public function index(): View
    {
        $cycleCounts = CycleCount::with(['warehouse', 'creator'])->latest()->paginate(15);
        return view('inventory.cycle-counts.index', compact('cycleCounts'));
    }

    public function create(): View
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        return view('inventory.cycle-counts.create', compact('warehouses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['warehouse_id' => 'required|exists:warehouses,id']);
        $cycleCount = CycleCount::create($validated);
        $inventory = Inventory::where('warehouse_id', $validated['warehouse_id'])->get();
        foreach ($inventory as $inv) {
            $cycleCount->items()->create(['product_id' => $inv->product_id, 'warehouse_location_id' => $inv->warehouse_location_id, 'system_quantity' => $inv->quantity_on_hand]);
        }
        return redirect()->route('inventory.cycle-counts.show', $cycleCount)->with('success', 'Cycle count created.');
    }

    public function show(CycleCount $cycleCount): View
    {
        $cycleCount->load(['warehouse', 'items.product', 'items.location']);
        return view('inventory.cycle-counts.show', compact('cycleCount'));
    }

    public function edit(CycleCount $cycleCount): View { return view('inventory.cycle-counts.edit', compact('cycleCount')); }

    public function update(Request $request, CycleCount $cycleCount): RedirectResponse
    {
        $cycleCount->update($request->only(['status']));
        return redirect()->route('inventory.cycle-counts.show', $cycleCount)->with('success', 'Updated.');
    }

    public function destroy(CycleCount $cycleCount): RedirectResponse
    {
        $cycleCount->delete();
        return redirect()->route('inventory.cycle-counts.index')->with('success', 'Cycle count deleted.');
    }

    public function countForm(CycleCount $cycleCount): View
    {
        $cycleCount->load(['items.product', 'items.location']);
        return view('inventory.cycle-counts.count', compact('cycleCount'));
    }

    public function count(Request $request, CycleCount $cycleCount): RedirectResponse
    {
        $validated = $request->validate(['items' => 'required|array', 'items.*.id' => 'required|exists:cycle_count_items,id', 'items.*.counted_quantity' => 'required|numeric|min:0']);
        foreach ($validated['items'] as $itemData) {
            $item = $cycleCount->items()->find($itemData['id']);
            $item->update(['counted_quantity' => $itemData['counted_quantity'], 'variance' => $itemData['counted_quantity'] - $item->system_quantity]);
        }
        return redirect()->route('inventory.cycle-counts.show', $cycleCount)->with('success', 'Count recorded.');
    }

    public function reconcile(CycleCount $cycleCount): RedirectResponse
    {
        $cycleCount->update(['status' => 'completed', 'completed_at' => now()]);
        return redirect()->route('inventory.cycle-counts.show', $cycleCount)->with('success', 'Cycle count reconciled.');
    }
}
