<x-app-layout>
    <x-slot name="title">{{ $invoice->invoice_number }}</x-slot>
    <div class="py-6 max-w-4xl space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $invoice->invoice_number }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ optional($invoice->vendor)->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-status-badge :status="$invoice->status" />
                    @if($invoice->status?->value === 'pending')
                    <form method="POST" action="{{ route('procurement.invoices.match', $invoice) }}">@csrf
                        <button class="px-3 py-1.5 bg-purple-600 text-white text-sm rounded-md">Run 3-Way Match</button>
                    </form>
                    @endif
                    @if(in_array($invoice->status?->value, ['pending', 'matched']) && auth()->user()->hasRole(['admin', 'manager']))
                    <form method="POST" action="{{ route('procurement.invoices.approve', $invoice) }}">@csrf
                        <button class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md">Approve</button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-4 gap-4 mt-4 text-sm">
                <div><p class="text-gray-500">PO</p><a href="{{ route('procurement.purchase-orders.show', $invoice->purchaseOrder) }}" class="font-medium text-blue-600">{{ optional($invoice->purchaseOrder)->po_number }}</a></div>
                <div><p class="text-gray-500">Invoice Date</p><p class="font-medium">{{ optional($invoice->invoice_date)->format('M d, Y') }}</p></div>
                <div><p class="text-gray-500">Due Date</p><p class="font-medium">{{ optional($invoice->due_date)->format('M d, Y') }}</p></div>
                <div><p class="text-gray-500">Match Status</p><x-status-badge :status="$invoice->three_way_match_status" /></div>
                <div><p class="text-gray-500">Subtotal</p><p class="font-medium">${{ number_format($invoice->subtotal, 2) }}</p></div>
                <div><p class="text-gray-500">Tax</p><p class="font-medium">${{ number_format($invoice->tax_amount, 2) }}</p></div>
                <div><p class="text-gray-500">Total</p><p class="font-bold text-lg">${{ number_format($invoice->total_amount, 2) }}</p></div>
            </div>
        </div>
    </div>
</x-app-layout>
