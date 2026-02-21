<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\WarehouseActivity;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseActivityController extends Controller
{
    public function index(Request $request): View
    {
        $activities = WarehouseActivity::with(['warehouse', 'user', 'product'])
            ->when($request->warehouse_id, fn($q, $w) => $q->where('warehouse_id', $w))
            ->latest()->paginate(20);
        $warehouses = Warehouse::where('is_active', true)->get();
        return view('warehouse.activities.index', compact('activities', 'warehouses'));
    }
}
