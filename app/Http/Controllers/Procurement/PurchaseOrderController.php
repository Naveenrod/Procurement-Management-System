<?php
namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\Product;
use App\Services\ProcurementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    public function __construct(private readonly ProcurementService $procurementService) {}

    public function index(Request $request): View
    {
        $purchaseOrders = PurchaseOrder::with('vendor')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15);
        return view('procurement.purchase-orders.index', compact('purchaseOrders'));
    }

    public function create(): View
    {
        $vendors = Vendor::where('status', 'approved')->get();
        $products = Product::orderBy('name')->get();
        return view('procurement.purchase-orders.create', compact('vendors', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);
        $po = $this->procurementService->createPurchaseOrder($validated);
        return redirect()->route('procurement.purchase-orders.show', $po)->with('success', 'Purchase order created.');
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['vendor', 'items.product', 'creator', 'approver', 'goodsReceipts', 'invoices']);
        return view('procurement.purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder): View
    {
        $vendors = Vendor::where('status', 'approved')->get();
        $products = Product::orderBy('name')->get();
        $purchaseOrder->load('items');
        return view('procurement.purchase-orders.edit', compact('purchaseOrder', 'vendors', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $purchaseOrder->update($validated);
        return redirect()->route('procurement.purchase-orders.show', $purchaseOrder)->with('success', 'Purchase order updated.');
    }

    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->delete();
        return redirect()->route('procurement.purchase-orders.index')->with('success', 'Purchase order deleted.');
    }

    public function approve(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $this->procurementService->approvePurchaseOrder($purchaseOrder);
        return redirect()->route('procurement.purchase-orders.show', $purchaseOrder)->with('success', 'Purchase order approved.');
    }

    public function send(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->update(['status' => 'sent']);
        return redirect()->route('procurement.purchase-orders.show', $purchaseOrder)->with('success', 'Purchase order sent to vendor.');
    }
}
