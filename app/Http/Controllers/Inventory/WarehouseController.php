<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(): View
    {
        $warehouses = Warehouse::withCount('locations')->paginate(15);
        return view('inventory.warehouses.index', compact('warehouses'));
    }

    public function create(): View { return view('inventory.warehouses.create'); }

    public function store(Request $request): RedirectResponse
    {
        $warehouse = Warehouse::create($request->validate(['name' => 'required|string', 'address' => 'nullable|string', 'city' => 'nullable|string', 'is_active' => 'boolean']));
        return redirect()->route('inventory.warehouses.show', $warehouse)->with('success', 'Warehouse created.');
    }

    public function show(Warehouse $warehouse): View
    {
        $warehouse->load('locations');
        return view('inventory.warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse): View { return view('inventory.warehouses.edit', compact('warehouse')); }

    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $warehouse->update($request->validate(['name' => 'required|string', 'address' => 'nullable|string', 'city' => 'nullable|string', 'is_active' => 'boolean']));
        return redirect()->route('inventory.warehouses.show', $warehouse)->with('success', 'Warehouse updated.');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $warehouse->delete();
        return redirect()->route('inventory.warehouses.index')->with('success', 'Warehouse deleted.');
    }
}
