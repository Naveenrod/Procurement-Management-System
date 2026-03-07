<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
    .page { padding: 40px; }

    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; border-bottom: 2px solid #b45309; padding-bottom: 20px; }
    .company-name { font-size: 20px; font-weight: bold; color: #b45309; }
    .company-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
    .doc-title { text-align: right; }
    .doc-title h1 { font-size: 22px; font-weight: bold; color: #1f2937; letter-spacing: 1px; }
    .doc-number { font-size: 13px; color: #6b7280; margin-top: 4px; }
    .status-pill { display: inline-block; margin-top: 6px; padding: 2px 10px; border-radius: 12px; font-size: 10px; font-weight: bold; background: #fef3c7; color: #92400e; text-transform: uppercase; }

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

    .value-box { background: #b45309; color: #fff; border-radius: 6px; padding: 20px 24px; text-align: center; margin-bottom: 24px; }
    .value-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.8; margin-bottom: 6px; }
    .value-amount { font-size: 28px; font-weight: bold; }

    .terms-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 16px; margin-bottom: 24px; }
    .terms-box h3 { font-size: 10px; text-transform: uppercase; color: #92400e; margin-bottom: 8px; }
    .terms-box p { font-size: 11px; color: #78350f; line-height: 1.6; }

    .sig-area { display: flex; gap: 32px; margin-top: 48px; }
    .sig-block { flex: 1; border-top: 1px solid #6b7280; padding-top: 8px; }
    .sig-label { font-size: 10px; color: #9ca3af; }

    .footer { border-top: 1px solid #e5e7eb; padding-top: 16px; display: flex; justify-content: space-between; font-size: 10px; color: #9ca3af; margin-top: 24px; }
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
            <h1>CONTRACT</h1>
            <div class="doc-number">{{ $contract->contract_number }}</div>
            <span class="status-pill">{{ ucwords(str_replace('_', ' ', $contract->status?->value ?? 'draft')) }}</span>
        </div>
    </div>

    <div class="meta">
        <div class="meta-block">
            <div class="meta-label">Title</div>
            <div class="meta-value">{{ $contract->title }}</div>
        </div>
        <div class="meta-block">
            <div class="meta-label">Start Date</div>
            <div class="meta-value">{{ optional($contract->start_date)->format('M d, Y') ?? '—' }}</div>
        </div>
        <div class="meta-block">
            <div class="meta-label">End Date</div>
            <div class="meta-value">{{ optional($contract->end_date)->format('M d, Y') ?? '—' }}</div>
        </div>
        <div class="meta-block">
            <div class="meta-label">Approved By</div>
            <div class="meta-value">{{ optional($contract->approver)->name ?? '—' }}</div>
        </div>
    </div>

    <div class="two-col">
        <div class="info-box">
            <h3>Vendor</h3>
            <div class="name">{{ optional($contract->vendor)->name ?? '—' }}</div>
            @if(optional($contract->vendor)->email)<div class="detail">{{ $contract->vendor->email }}</div>@endif
            @if(optional($contract->vendor)->phone)<div class="detail">{{ $contract->vendor->phone }}</div>@endif
            @if(optional($contract->vendor)->address)<div class="detail">{{ $contract->vendor->address }}</div>@endif
        </div>
        <div class="info-box">
            <h3>Created By</h3>
            <div class="name">{{ optional($contract->creator)->name ?? '—' }}</div>
            @if($contract->approved_at)
            <div style="margin-top:10px;">
                <div style="font-size:10px;text-transform:uppercase;color:#9ca3af;margin-bottom:4px;">Approved On</div>
                <div class="name" style="font-size:12px;">{{ $contract->approved_at->format('M d, Y') }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="value-box">
        <div class="value-label">Contract Value</div>
        <div class="value-amount">${{ number_format($contract->value ?? 0, 2) }}</div>
    </div>

    @if($contract->terms)
    <div class="terms-box">
        <h3>Terms &amp; Conditions</h3>
        <p>{{ $contract->terms }}</p>
    </div>
    @endif

    <div class="sig-area">
        <div class="sig-block">
            <div class="sig-label">Authorized Signature (Vendor)</div>
        </div>
        <div class="sig-block">
            <div class="sig-label">Authorized Signature (Company)</div>
        </div>
    </div>

    <div class="footer">
        <span>Generated on {{ now()->format('M d, Y H:i') }}</span>
        <span>{{ $contract->contract_number }}</span>
    </div>
</div>
</body>
</html>
