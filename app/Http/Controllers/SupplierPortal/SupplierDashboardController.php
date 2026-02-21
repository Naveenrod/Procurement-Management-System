<?php
namespace App\Http\Controllers\SupplierPortal;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Invoice;
use Illuminate\View\View;

class SupplierDashboardController extends Controller
{
    public function index(): View
    {
        $vendorId = auth()->user()->vendor_id;
        $openPOs = PurchaseOrder::where('vendor_id', $vendorId)->whereIn('status', ['sent', 'approved'])->count();
        $pendingInvoices = Invoice::where('vendor_id', $vendorId)->where('status', 'pending')->count();
        $recentPOs = PurchaseOrder::where('vendor_id', $vendorId)->latest()->limit(5)->get();
        return view('supplier.dashboard', compact('openPOs', 'pendingInvoices', 'recentPOs'));
    }
}
