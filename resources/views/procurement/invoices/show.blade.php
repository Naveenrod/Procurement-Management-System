<x-app-layout>
    <x-slot name="title">{{ $invoice->invoice_number }}</x-slot>
    <div class="py-6 max-w-4xl space-y-4">

        @if(session('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $invoice->invoice_number }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ optional($invoice->vendor)->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('procurement.invoices.pdf', $invoice) }}"
                       class="px-3 py-1.5 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700">
                        Download PDF
                    </a>
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

        {{-- Line Items --}}
        @if($invoice->items->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Invoice Items</h3></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-right">Qty</th>
                        <th class="px-4 py-3 text-right">Unit Price</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ optional(optional($item->purchaseOrderItem)->product)->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($item->quantity ?? 0, 2) }}</td>
                        <td class="px-4 py-3 text-right">${{ number_format($item->unit_price ?? 0, 2) }}</td>
                        <td class="px-4 py-3 text-right font-medium">${{ number_format($item->total_price ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold">
                        <td colspan="3" class="px-4 py-3 text-right">Total</td>
                        <td class="px-4 py-3 text-right">${{ number_format($invoice->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>
