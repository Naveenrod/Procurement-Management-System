<?php

namespace App\Http\Controllers\SupplierPortal;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class SupplierDeliveryController extends Controller
{
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'estimated_delivery_date' => 'required|date',
            'tracking_number' => 'nullable|string|max:255',
            'delivery_notes' => 'nullable|string',
        ]);

        $purchaseOrder->update([
            'expected_delivery_date' => $validated['estimated_delivery_date'],
            'tracking_number' => $validated['tracking_number'] ?? null,
            'notes' => $validated['delivery_notes'] ?? $purchaseOrder->notes,
        ]);

        return back()->with('success', 'Delivery information updated.');
    }
}
