@extends('layouts.supplier')
@section('title', $invoice->invoice_number)
@section('content')
<div class="py-6 max-w-2xl">
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $invoice->invoice_number }}</h2>
                <p class="text-sm text-gray-500">PO: {{ optional($invoice->purchaseOrder)->po_number }}</p>
            </div>
            <x-status-badge :status="$invoice->status" />
        </div>
        <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
            <div><p class="text-gray-500">Invoice Date</p><p class="font-medium">{{ optional($invoice->invoice_date)->format('M d, Y') }}</p></div>
            <div><p class="text-gray-500">Due Date</p><p class="font-medium">{{ optional($invoice->due_date)->format('M d, Y') }}</p></div>
            <div><p class="text-gray-500">Total</p><p class="font-bold text-xl">${{ number_format($invoice->total_amount, 2) }}</p></div>
            <div><p class="text-gray-500">3-Way Match</p><x-status-badge :status="$invoice->three_way_match_status" /></div>
        </div>
    </div>
</div>
@endsection
