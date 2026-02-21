<?php
namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseOrder;
use App\Services\ProcurementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    public function __construct(private readonly ProcurementService $procurementService) {}

    public function index(Request $request): View
    {
        $orders = PurchaseOrder::with('vendor')
            ->when($request->search, fn($q, $s) => $q->where('po_number', 'like', "%$s%")
                ->orWhereHas('vendor', fn($q) => $q->where('name', 'like', "%$s%")))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15);
        return view('procurement.purchase-orders.index', compact('orders'));
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
        $po = $this->procurementService->createPurchaseOrder(
            \Arr::except($validated, ['items']),
            $validated['items']
        );
        return redirect()->route('procurement.purchase-orders.show', $po)->with('success', 'Purchase order created.');
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['vendor', 'items.product', 'creator', 'approver', 'goodsReceipts', 'invoices']);
        $order = $purchaseOrder;
        return view('procurement.purchase-orders.show', compact('order'));
    }

    public function edit(PurchaseOrder $purchaseOrder): View
    {
        $vendors = Vendor::where('status', 'approved')->get();
        $products = Product::orderBy('name')->get();
        $purchaseOrder->load('items');
        $order = $purchaseOrder;
        return view('procurement.purchase-orders.edit', compact('order', 'vendors', 'products'));
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

        // Auto-create an inbound warehouse order so the WMS receiving queue is populated
        $warehouse = Warehouse::where('is_active', true)->first();
        if ($warehouse && !WarehouseOrder::where('purchase_order_id', $purchaseOrder->id)->exists()) {
            $purchaseOrder->load('items');
            $wo = WarehouseOrder::create([
                'warehouse_id'      => $warehouse->id,
                'type'              => 'inbound',
                'status'            => 'pending',
                'purchase_order_id' => $purchaseOrder->id,
            ]);
            foreach ($purchaseOrder->items as $item) {
                $wo->items()->create([
                    'product_id'        => $item->product_id,
                    'expected_quantity' => $item->quantity,
                    'status'            => 'pending',
                ]);
            }
        }

        return redirect()->route('procurement.purchase-orders.show', $purchaseOrder)->with('success', 'Purchase order sent to vendor.');
    }
}
