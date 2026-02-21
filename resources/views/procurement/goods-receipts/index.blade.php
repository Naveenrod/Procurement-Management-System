<x-app-layout>
    <x-slot name="title">Goods Receipts</x-slot>
    <div class="py-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Goods Receipts</h2>
            <a href="{{ route('goods-receipts.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New Receipt</a>
        </div>
        @if($receipts->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Receipt #</th>
                        <th class="px-4 py-3 text-left">PO Number</th>
                        <th class="px-4 py-3 text-left">Received By</th>
                        <th class="px-4 py-3 text-left">Received At</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($receipts as $receipt)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $receipt->receipt_number }}</td>
                        <td class="px-4 py-3"><a href="{{ route('purchase-orders.show', $receipt->purchaseOrder) }}" class="text-blue-600 hover:underline">{{ optional($receipt->purchaseOrder)->po_number }}</a></td>
                        <td class="px-4 py-3">{{ optional($receipt->receiver)->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($receipt->received_at)->format('M d, Y') }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$receipt->status" /></td>
                        <td class="px-4 py-3"><a href="{{ route('goods-receipts.show', $receipt) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $receipts->links() }}</div>
        @else
        <x-empty-state title="No goods receipts" action-url="{{ route('goods-receipts.create') }}" action-label="New Receipt" />
        @endif
    </div>
</x-app-layout>
