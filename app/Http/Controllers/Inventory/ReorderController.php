<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Services\InventoryService;
use Illuminate\View\View;

class ReorderController extends Controller
{
    public function __construct(private readonly InventoryService $inventoryService) {}

    public function index(): View
    {
        $alerts = $this->inventoryService->getReorderAlerts();
        return view('inventory.reorders.index', compact('alerts'));
    }
}
