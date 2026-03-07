<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
    .page { padding: 40px; }

    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; border-bottom: 2px solid #7c3aed; padding-bottom: 20px; }
    .company-name { font-size: 20px; font-weight: bold; color: #7c3aed; }
    .company-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
    .doc-title { text-align: right; }
    .doc-title h1 { font-size: 22px; font-weight: bold; color: #1f2937; letter-spacing: 1px; }
    .doc-number { font-size: 13px; color: #6b7280; margin-top: 4px; }
    .status-pill { display: inline-block; margin-top: 6px; padding: 2px 10px; border-radius: 12px; font-size: 10px; font-weight: bold; background: #ede9fe; color: #5b21b6; text-transform: uppercase; }

    .meta { display: flex; gap: 0; margin-bottom: 24px; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; }
    .meta-block { flex: 1; padding: 12px 16px; border-right: 1px solid #e5e7eb; }
    .meta-block:last-child { border-right: none; }
    .meta-label { font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .meta-value { font-size: 12px; font-weight: 600; color: #111827; }

    .description-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 16px; margin-bottom: 24px; font-size: 12px; color: #374151; }

    .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #6b7280; letter-spacing: 0.5px; margin-bottom: 8px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    thead tr { background: #7c3aed; color: #fff; }
    thead th { padding: 9px 12px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
    thead th.right { text-align: right; }
    thead th.center { text-align: center; }
    tbody tr { border-bottom: 1px solid #f3f4f6; }
    tbody td { padding: 9px 12px; font-size: 12px; }
    tbody td.right { text-align: right; }
    tbody td.center { text-align: center; }
    .selected-row { background: #f0fdf4; }

    .vendor-chips { margin-bottom: 24px; }
    .chip { display: inline-block; padding: 3px 10px; background: #ede9fe; color: #5b21b6; border-radius: 12px; font-size: 10px; margin: 2px; }

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
            <h1>REQUEST FOR QUOTATION</h1>
            <div class="doc-number">{{ $rfq->rfq_number }}</div>
            <span class="status-pill">{{ ucwords(str_replace('_', ' ', $rfq->status?->value ?? 'draft')) }}</span>
        </div>
    </div>

    <div class="meta">
        <div class="meta-block">
            <div class="meta-label">Title</div>
            <div class="meta-value">{{ $rfq->title }}</div>
        </div>
        <div class="meta-block">
            <div class="meta-label">Issue Date</div>
            <div class="meta-value">{{ optional($rfq->issue_date)->format('M d, Y') ?? '—' }}</div>
        </div>
        <div class="meta-block">
            <div class="meta-label">Closing Date</div>
            <div class="meta-value">{{ optional($rfq->closing_date)->format('M d, Y') ?? '—' }}</div>
        </div>
        <div class="meta-block">
            <div class="meta-label">Issued By</div>
            <div class="meta-value">{{ optional($rfq->issuer)->name ?? '—' }}</div>
        </div>
    </div>

    @if($rfq->description)
    <div class="description-box">{{ $rfq->description }}</div>
    @endif

    {{-- Invited Vendors --}}
    @if($rfq->vendors->count())
    <div class="section-title">Invited Vendors</div>
    <div class="vendor-chips" style="margin-bottom:24px;">
        @foreach($rfq->vendors as $rfqVendor)
        <span class="chip">{{ optional($rfqVendor->vendor)->name ?? '—' }}</span>
        @endforeach
    </div>
    @endif

    {{-- Items --}}
    @if($rfq->items->count())
    <div class="section-title">Items Requested</div>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th class="right">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rfq->items as $item)
            <tr>
                <td>{{ optional($item->product)->name ?? '—' }}</td>
                <td class="right">{{ number_format($item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Responses --}}
    @if($rfq->responses->count())
    <div class="section-title">Vendor Responses</div>
    <table>
        <thead>
            <tr>
                <th>Vendor</th>
                <th class="right">Total Amount</th>
                <th class="center">Delivery (days)</th>
                <th>Payment Terms</th>
                <th class="center">Selected</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rfq->responses as $response)
            <tr class="{{ $response->is_selected ? 'selected-row' : '' }}">
                <td>{{ optional($response->vendor)->name ?? '—' }}</td>
                <td class="right">${{ number_format($response->total_amount ?? 0, 2) }}</td>
                <td class="center">{{ $response->delivery_days ?? '—' }}</td>
                <td>{{ $response->payment_terms ?? '—' }}</td>
                <td class="center">{{ $response->is_selected ? '✓' : '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <span>Generated on {{ now()->format('M d, Y H:i') }}</span>
        <span>{{ $rfq->rfq_number }}</span>
    </div>
</div>
</body>
</html>
