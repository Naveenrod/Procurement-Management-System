<?php
namespace App\Http\Controllers\SupplierPortal;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierPurchaseOrderController extends Controller
{
    public function index(): View
    {
        $orders = PurchaseOrder::where('vendor_id', auth()->user()->vendor_id)->latest()->paginate(15);
        return view('supplier.purchase-orders.index', compact('orders'));
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        abort_unless($purchaseOrder->vendor_id === auth()->user()->vendor_id, 403);
        $purchaseOrder->load('items.product');
        $order = $purchaseOrder;
        return view('supplier.purchase-orders.show', compact('order'));
    }

    public function acknowledge(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        abort_unless($purchaseOrder->vendor_id === auth()->user()->vendor_id, 403);
        $purchaseOrder->update(['status' => 'acknowledged']);
        return redirect()->route('supplier.purchase-orders.show', $purchaseOrder)->with('success', 'PO acknowledged.');
    }
}
