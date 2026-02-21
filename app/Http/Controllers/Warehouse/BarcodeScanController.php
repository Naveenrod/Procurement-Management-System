<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Services\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarcodeScanController extends Controller
{
    public function __construct(private readonly WarehouseService $warehouseService) {}

    public function index(): View { return view('warehouse.scan.index'); }

    public function scan(Request $request)
    {
        $request->validate(['barcode' => 'required|string']);
        $result = $this->warehouseService->processBarcodeScan($request->barcode);
        if ($request->wantsJson()) { return response()->json($result); }
        return back()->with('scan_result', $result);
    }
}
