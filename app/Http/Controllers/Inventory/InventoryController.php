<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Warehouse;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct(private readonly InventoryService $inventoryService) {}

    public function index(Request $request): View
    {
        $inventory = Inventory::with(['product', 'warehouse', 'location'])
            ->when($request->warehouse_id, fn($q, $w) => $q->where('warehouse_id', $w))
            ->when($request->search, fn($q, $s) => $q->whereHas('product', fn($pq) => $pq->where('name', 'like', "%{$s}%")))
            ->paginate(20);
        $warehouses = Warehouse::where('is_active', true)->get();
        return view('inventory.stock.index', compact('inventory', 'warehouses'));
    }

    public function adjustForm(): View
    {
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::orderBy('name')->get();
        return view('inventory.stock.adjust', compact('warehouses', 'products'));
    }

    public function adjust(Request $request): RedirectResponse
    {
        $validated = $request->validate(['product_id' => 'required|exists:products,id', 'warehouse_id' => 'required|exists:warehouses,id', 'type' => 'required|in:add,remove', 'quantity' => 'required|numeric|min:0.01', 'notes' => 'nullable|string']);
        $product = Product::findOrFail($validated['product_id']);
        $warehouse = Warehouse::findOrFail($validated['warehouse_id']);
        $qty = $validated['type'] === 'add' ? (int) $validated['quantity'] : -(int) $validated['quantity'];
        $this->inventoryService->adjustStock($product, $warehouse, $qty, 'adjustment', $validated['notes'] ?? null);
        return redirect()->route('inventory.stock.index')->with('success', 'Stock adjusted successfully.');
    }
}
