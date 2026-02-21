<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseLocationController extends Controller
{
    public function index(Warehouse $warehouse): View
    {
        $locations = $warehouse->locations()->paginate(20);
        return view('inventory.warehouses.locations', compact('warehouse', 'locations'));
    }

    public function store(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $warehouse->locations()->create($request->validate(['zone' => 'required|string', 'aisle' => 'required|string', 'rack' => 'required|string', 'shelf' => 'required|string', 'bin' => 'nullable|string', 'capacity' => 'integer|min:1']));
        return redirect()->route('inventory.warehouses.show', $warehouse)->with('success', 'Location added.');
    }

    public function update(Request $request, Warehouse $warehouse, WarehouseLocation $location): RedirectResponse
    {
        $location->update($request->validate(['zone' => 'required|string', 'aisle' => 'required|string', 'rack' => 'required|string', 'shelf' => 'required|string', 'bin' => 'nullable|string', 'capacity' => 'integer|min:1']));
        return redirect()->route('inventory.warehouses.show', $warehouse)->with('success', 'Location updated.');
    }

    public function destroy(Warehouse $warehouse, WarehouseLocation $location): RedirectResponse
    {
        $location->delete();
        return redirect()->route('inventory.warehouses.show', $warehouse)->with('success', 'Location removed.');
    }
}
