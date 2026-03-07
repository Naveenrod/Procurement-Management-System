<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
    .page { padding: 40px; }

    /* Header */
    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; }
    .company-name { font-size: 20px; font-weight: bold; color: #4f46e5; }
    .company-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
    .doc-title { text-align: right; }
    .doc-title h1 { font-size: 22px; font-weight: bold; color: #1f2937; letter-spacing: 1px; }
    .doc-title .doc-number { font-size: 13px; color: #6b7280; margin-top: 4px; }
    .status-pill { display: inline-block; margin-top: 6px; padding: 2px 10px; border-radius: 12px; font-size: 10px; font-weight: bold; background: #e0e7ff; color: #3730a3; text-transform: uppercase; }

    /* Meta grid */
    .meta { display: flex; gap: 0; margin-bottom: 24px; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; }
    .meta-block { flex: 1; padding: 12px 16px; border-right: 1px solid #e5e7eb; }
    .meta-block:last-child { border-right: none; }
    .meta-label { font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .meta-value { font-size: 12px; font-weight: 600; color: #111827; }

    /* Vendor box */
    .vendor-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 16px; margin-bottom: 24px; }
    .vendor-box h3 { font-size: 10px; text-transform: uppercase; color: #9ca3af; letter-spacing: 0.5px; margin-bottom: 8px; }
    .vendor-box .vendor-name { font-size: 14px; font-weight: bold; color: #111827; }
    .vendor-box .vendor-detail { font-size: 11px; color: #6b7280; margin-top: 2px; }

    /* Line items table */
    .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #6b7280; letter-spacing: 0.5px; margin-bottom: 8px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    thead tr { background: #4f46e5; color: #fff; }
    thead th { padding: 9px 12px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
    thead th.right { text-align: right; }
    tbody tr { border-bottom: 1px solid #f3f4f6; }
    tbody tr:last-child { border-bottom: none; }
    tbody td { padding: 9px 12px; font-size: 12px; }
    tbody td.right { text-align: right; }
    .total-row { background: #f3f4f6; font-weight: bold; }

    /* Totals */
    .totals { float: right; width: 260px; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 32px; }
    .totals-row { display: flex; justify-content: space-between; padding: 8px 16px; border-bottom: 1px solid #e5e7eb; font-size: 12px; }
    .totals-row:last-child { border-bottom: none; background: #4f46e5; color: #fff; font-weight: bold; font-size: 13px; }
    .clearfix { clear: both; }

    /* Notes */
    .notes-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 12px 16px; margin-bottom: 24px; }
    .notes-box .notes-label { font-size: 10px; text-transform: uppercase; color: #92400e; margin-bottom: 4px; }
    .notes-box p { font-size: 11px; color: #78350f; }

    /* Footer */
    .footer { border-top: 1px solid #e5e7eb; padding-top: 16px; display: flex; justify-content: space-between; font-size: 10px; color: #9ca3af; }
</style>
</head>
<body>
<div class="page">
    {{-- Header --}}
    <div class="header">
        <div>
            <div class="company-name">ProcureMS</div>
            <div class="company-sub">Procurement Management System</div>
        </div>
        <div class="doc-title">
            <h1>PURCHASE ORDER</h1>
            <div class="doc-number">{{ $order->po_number }}</div>
            <span class="status-pill">{{ ucwords(str_replace('_', ' ', $order->status?->value ?? 'draft')) }}</span>
        </div>
    </div>

    {{-- Meta row --}}
    <div class="meta">
        <div class="meta-block">
            <div class="meta-label">Order Date</div>
            <div class="meta-value">{{ optional($order->order_date)->format('M d, Y') ?? '—' }}</div>
        </div>
        <div class="meta-block">
            <div class="meta-label">Expected Delivery</div>
            <div class="meta-value">{{ optional($order->expected_delivery_date)->format('M d, Y') ?? '—' }}</div>
        </div>
        <div class="meta-block">
            <div class="meta-label">Created By</div>
            <div class="meta-value">{{ optional($order->creator)->name ?? '—' }}</div>
        </div>
        <div class="meta-block">
            <div class="meta-label">Approved By</div>
            <div class="meta-value">{{ optional($order->approver)->name ?? '—' }}</div>
        </div>
    </div>

    {{-- Vendor --}}
    <div class="vendor-box">
        <h3>Vendor</h3>
        <div class="vendor-name">{{ optional($order->vendor)->name ?? '—' }}</div>
        @if(optional($order->vendor)->email)<div class="vendor-detail">{{ $order->vendor->email }}</div>@endif
        @if(optional($order->vendor)->phone)<div class="vendor-detail">{{ $order->vendor->phone }}</div>@endif
        @if(optional($order->vendor)->address)<div class="vendor-detail">{{ $order->vendor->address }}</div>@endif
    </div>

    {{-- Line Items --}}
    <div class="section-title">Line Items</div>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th class="right">Qty</th>
                <th class="right">Received</th>
                <th class="right">Unit Price</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ optional($item->product)->name ?? '—' }}</td>
                <td class="right">{{ number_format($item->quantity, 2) }}</td>
                <td class="right">{{ number_format($item->received_quantity, 2) }}</td>
                <td class="right">${{ number_format($item->unit_price, 2) }}</td>
                <td class="right">${{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals">
        <div class="totals-row"><span>Subtotal</span><span>${{ number_format($order->subtotal ?? 0, 2) }}</span></div>
        <div class="totals-row"><span>Tax</span><span>${{ number_format($order->tax_amount ?? 0, 2) }}</span></div>
        <div class="totals-row"><span>Total</span><span>${{ number_format($order->total_amount, 2) }}</span></div>
    </div>
    <div class="clearfix"></div>

    {{-- Notes --}}
    @if($order->notes)
    <div class="notes-box">
        <div class="notes-label">Notes</div>
        <p>{{ $order->notes }}</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <span>Generated on {{ now()->format('M d, Y H:i') }}</span>
        <span>{{ $order->po_number }}</span>
    </div>
</div>
</body>
</html>
