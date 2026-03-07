<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
    .page { padding: 40px; }

    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; border-bottom: 2px solid #059669; padding-bottom: 20px; }
    .company-name { font-size: 20px; font-weight: bold; color: #059669; }
    .company-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
    .doc-title { text-align: right; }
    .doc-title h1 { font-size: 22px; font-weight: bold; color: #1f2937; letter-spacing: 1px; }
    .doc-number { font-size: 13px; color: #6b7280; margin-top: 4px; }
    .status-pill { display: inline-block; margin-top: 6px; padding: 2px 10px; border-radius: 12px; font-size: 10px; font-weight: bold; background: #d1fae5; color: #065f46; text-transform: uppercase; }

    .meta { display: flex; gap: 0; margin-bottom: 24px; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; }
    .meta-block { flex: 1; padding: 12px 16px; border-right: 1px solid #e5e7eb; }
    .meta-block:last-child { border-right: none; }
    .meta-label { font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .meta-value { font-size: 12px; font-weight: 600; color: #111827; }

    .two-col { display: flex; gap: 16px; margin-bottom: 24px; }
    .info-box { flex: 1; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 16px; }
    .info-box h3 { font-size: 10px; text-transform: uppercase; color: #9ca3af; letter-spacing: 0.5px; margin-bottom: 8px; }
    .info-box .name { font-size: 14px; font-weight: bold; color: #111827; }
    .info-box .detail { font-size: 11px; color: #6b7280; margin-top: 2px; }

    .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #6b7280; letter-spacing: 0.5px; margin-bottom: 8px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    thead tr { background: #059669; color: #fff; }
    thead th { padding: 9px 12px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
    thead th.right { text-align: right; }
    tbody tr { border-bottom: 1px solid #f3f4f6; }
    tbody td { padding: 9px 12px; font-size: 12px; }
    tbody td.right { text-align: right; }

    .totals { float: right; width: 260px; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 32px; }
    .totals-row { display: flex; justify-content: space-between; padding: 8px 16px; border-bottom: 1px solid #e5e7eb; font-size: 12px; }
    .totals-row:last-child { border-bottom: none; background: #059669; color: #fff; font-weight: bold; font-size: 13px; }
    .clearfix { clear: both; }

    .footer { border-top: 1px solid #e5e7eb; padding-top: 16px; display: flex; justify-content: space-between; font-size: 10px; color: #9ca3af; }
</style>
</head>
<body>
<div class="page">
    <div class="header">
        <div>
            <div class="company-name">ProcureMS</div>
            <div class="company-sub">Procurement Management System</div>
        </div>
        <div class="doc-title">
            <h1>INVOICE</h1>
            <div class="doc-number">{{ $invoice->invoice_number }}</div>
            <span class="status-pill">{{ ucwords(str_replace('_', ' ', $invoice->status?->value ?? 'pending')) }}</span>
        </div>
    </div>

    <div class="meta">
        <div class="meta-block">
            <div class="meta-label">Invoice Date</div>
            <div class="meta-value">{{ optional($invoice->invoice_date)->format('M d, Y') ?? '—' }}</div>
        </div>
        <div class="meta-block">
            <div class="meta-label">Due Date</div>
            <div class="meta-value">{{ optional($invoice->due_date)->format('M d, Y') ?? '—' }}</div>
        </div>
        <div class="meta-block">
            <div class="meta-label">Related PO</div>
            <div class="meta-value">{{ optional($invoice->purchaseOrder)->po_number ?? '—' }}</div>
        </div>
        <div class="meta-block">
            <div class="meta-label">Match Status</div>
            <div class="meta-value">{{ ucwords(str_replace('_', ' ', $invoice->three_way_match_status?->value ?? '—')) }}</div>
        </div>
    </div>

    <div class="two-col">
        <div class="info-box">
            <h3>Vendor</h3>
            <div class="name">{{ optional($invoice->vendor)->name ?? '—' }}</div>
            @if(optional($invoice->vendor)->email)<div class="detail">{{ $invoice->vendor->email }}</div>@endif
            @if(optional($invoice->vendor)->phone)<div class="detail">{{ $invoice->vendor->phone }}</div>@endif
        </div>
        <div class="info-box">
            <h3>Submitted By</h3>
            <div class="name">{{ optional($invoice->submitter)->name ?? '—' }}</div>
            @if(optional($invoice->approver)->name)
            <div class="detail" style="margin-top:8px; font-size:10px; color:#9ca3af; text-transform:uppercase;">Approved By</div>
            <div class="name" style="font-size:12px;">{{ $invoice->approver->name }}</div>
            @endif
        </div>
    </div>

    @if($invoice->items->count())
    <div class="section-title">Invoice Items</div>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th class="right">Qty</th>
                <th class="right">Unit Price</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ optional(optional($item->purchaseOrderItem)->product)->name ?? '—' }}</td>
                <td class="right">{{ number_format($item->quantity ?? 0, 2) }}</td>
                <td class="right">${{ number_format($item->unit_price ?? 0, 2) }}</td>
                <td class="right">${{ number_format($item->total_price ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="totals">
        <div class="totals-row"><span>Subtotal</span><span>${{ number_format($invoice->subtotal ?? 0, 2) }}</span></div>
        <div class="totals-row"><span>Tax</span><span>${{ number_format($invoice->tax_amount ?? 0, 2) }}</span></div>
        <div class="totals-row"><span>Total</span><span>${{ number_format($invoice->total_amount, 2) }}</span></div>
    </div>
    <div class="clearfix"></div>

    @if($invoice->notes)
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:6px;padding:12px 16px;margin-bottom:24px;">
        <div style="font-size:10px;text-transform:uppercase;color:#92400e;margin-bottom:4px;">Notes</div>
        <p style="font-size:11px;color:#78350f;">{{ $invoice->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <span>Generated on {{ now()->format('M d, Y H:i') }}</span>
        <span>{{ $invoice->invoice_number }}</span>
    </div>
</div>
</body>
</html>
