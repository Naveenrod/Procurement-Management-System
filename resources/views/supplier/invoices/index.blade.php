@extends('layouts.supplier')
@section('title', 'My Invoices')
@section('content')
<div class="py-6">
    @if($invoices->count())
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3 text-left">Invoice #</th><th class="px-4 py-3 text-left">PO #</th><th class="px-4 py-3 text-left">Invoice Date</th><th class="px-4 py-3 text-right">Total</th><th class="px-4 py-3 text-left">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y">
                @foreach($invoices as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-3"><a href="{{ route('supplier.purchase-orders.show', $invoice->purchaseOrder) }}" class="text-blue-600 hover:underline text-xs">{{ optional($invoice->purchaseOrder)->po_number }}</a></td>
                    <td class="px-4 py-3 text-gray-500">{{ optional($invoice->invoice_date)->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right font-medium">${{ number_format($invoice->total_amount, 2) }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$invoice->status" /></td>
                    <td class="px-4 py-3"><a href="{{ route('supplier.invoices.show', $invoice) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $invoices->links() }}</div>
    @else
    <x-empty-state title="No invoices" description="Invoices you submit will appear here." />
    @endif
</div>
@endsection
