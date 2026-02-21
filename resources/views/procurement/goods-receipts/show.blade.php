<x-app-layout>
    <x-slot name="title">{{ $receipt->receipt_number }}</x-slot>
    <div class="py-6 max-w-4xl space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $receipt->receipt_number }}</h2>
                    <p class="text-sm text-gray-500 mt-1">PO: <a href="{{ route('procurement.purchase-orders.show', $receipt->purchaseOrder) }}" class="text-blue-600">{{ optional($receipt->purchaseOrder)->po_number }}</a></p>
                </div>
                <x-status-badge :status="$receipt->status" />
            </div>
            <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
                <div><p class="text-gray-500">Received By</p><p class="font-medium">{{ optional($receipt->receiver)->name }}</p></div>
                <div><p class="text-gray-500">Received At</p><p class="font-medium">{{ optional($receipt->received_at)->format('M d, Y H:i') }}</p></div>
                @if($receipt->notes)<div class="col-span-3"><p class="text-gray-500">Notes</p><p>{{ $receipt->notes }}</p></div>@endif
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Items</h3></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-right">Qty Received</th>
                        <th class="px-4 py-3 text-right">Qty Accepted</th>
                        <th class="px-4 py-3 text-right">Qty Rejected</th>
                        <th class="px-4 py-3 text-left">Rejection Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($receipt->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ optional(optional($item->purchaseOrderItem)->product)->name }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($item->quantity_received, 2) }}</td>
                        <td class="px-4 py-3 text-right text-green-700">{{ number_format($item->quantity_accepted, 2) }}</td>
                        <td class="px-4 py-3 text-right text-red-600">{{ number_format($item->quantity_rejected, 2) }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $item->rejection_reason ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
