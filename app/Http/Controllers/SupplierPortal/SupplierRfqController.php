<?php
namespace App\Http\Controllers\SupplierPortal;

use App\Http\Controllers\Controller;
use App\Models\Rfq;
use App\Models\RfqResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierRfqController extends Controller
{
    public function index(): View
    {
        $vendorId = auth()->user()->vendor_id;
        $rfqs = Rfq::whereHas('vendors', fn($q) => $q->where('vendor_id', $vendorId))->where('status', 'published')->latest()->paginate(15);
        return view('supplier.rfqs.index', compact('rfqs'));
    }

    public function show(Rfq $rfq): View
    {
        $vendorId = auth()->user()->vendor_id;
        abort_unless($rfq->vendors()->where('vendor_id', $vendorId)->exists(), 403);
        $rfq->load('items.product');
        $myResponse = $rfq->responses()->where('vendor_id', $vendorId)->first();
        return view('supplier.rfqs.show', compact('rfq', 'myResponse'));
    }

    public function respond(Request $request, Rfq $rfq): RedirectResponse
    {
        $vendorId = auth()->user()->vendor_id;
        abort_unless($rfq->vendors()->where('vendor_id', $vendorId)->exists(), 403);
        $validated = $request->validate(['total_amount' => 'required|numeric|min:0', 'delivery_days' => 'required|integer|min:1', 'notes' => 'nullable|string', 'items' => 'required|array', 'items.*.rfq_item_id' => 'required|exists:rfq_items,id', 'items.*.unit_price' => 'required|numeric|min:0']);
        $response = RfqResponse::updateOrCreate(['rfq_id' => $rfq->id, 'vendor_id' => $vendorId], array_merge($validated, ['submitted_at' => now()]));
        foreach ($validated['items'] as $item) {
            $rfqItem = $rfq->items()->find($item['rfq_item_id']);
            $response->items()->updateOrCreate(['rfq_item_id' => $item['rfq_item_id']], ['unit_price' => $item['unit_price'], 'total_price' => $item['unit_price'] * ($rfqItem?->quantity ?? 1)]);
        }
        return redirect()->route('supplier.rfqs.show', $rfq)->with('success', 'Response submitted.');
    }
}
