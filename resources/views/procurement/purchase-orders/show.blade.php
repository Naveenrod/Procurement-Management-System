<x-app-layout>
    <x-slot name="title">{{ $order->po_number }}</x-slot>
    <div class="py-6 max-w-5xl space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $order->po_number }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ optional($order->vendor)->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-status-badge :status="$order->status" />
                    @if($order->status === 'draft' && auth()->user()->hasRole(['admin','manager']))
                    <form method="POST" action="{{ route('purchase-orders.approve', $order) }}">@csrf
                        <button class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md">Approve</button>
                    </form>
                    @endif
                    @if($order->status === 'approved')
                    <form method="POST" action="{{ route('purchase-orders.send', $order) }}">@csrf
                        <button class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md">Send to Vendor</button>
                    </form>
                    @endif
                    @if(in_array($order->status, ['sent','approved']))
                    <a href="{{ route('goods-receipts.create', ['po_id' => $order->id]) }}" class="px-3 py-1.5 bg-purple-600 text-white text-sm rounded-md">Receive Goods</a>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-4 gap-4 mt-6 text-sm">
                <div><p class="text-gray-500">Order Date</p><p class="font-medium">{{ optional($order->order_date)->format('M d, Y') }}</p></div>
                <div><p class="text-gray-500">Expected Delivery</p><p class="font-medium">{{ optional($order->expected_delivery_date)->format('M d, Y') ?? '—' }}</p></div>
                <div><p class="text-gray-500">Created By</p><p class="font-medium">{{ optional($order->creator)->name ?? '—' }}</p></div>
                <div><p class="text-gray-500">Approved By</p><p class="font-medium">{{ optional($order->approver)->name ?? '—' }}</p></div>
            </div>
            @if($order->notes)<p class="mt-4 text-sm text-gray-600">{{ $order->notes }}</p>@endif
        </div>

        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Line Items</h3></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-right">Qty</th>
                        <th class="px-4 py-3 text-right">Received</th>
                        <th class="px-4 py-3 text-right">Unit Price</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ optional($item->product)->name }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($item->quantity, 2) }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($item->received_quantity, 2) }}</td>
                        <td class="px-4 py-3 text-right">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-3 text-right font-medium">${{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold">
                        <td colspan="4" class="px-4 py-3 text-right">Total</td>
                        <td class="px-4 py-3 text-right">${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($order->goodsReceipts->count())
        <div class="bg-white rounded-lg shadow-sm border p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Goods Receipts</h3>
            @foreach($order->goodsReceipts as $gr)
            <div class="flex justify-between items-center py-2 border-b last:border-0">
                <a href="{{ route('goods-receipts.show', $gr) }}" class="text-blue-600 hover:underline text-sm">{{ $gr->receipt_number }}</a>
                <x-status-badge :status="$gr->status" />
                <span class="text-xs text-gray-500">{{ optional($gr->received_at)->format('M d, Y') }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</x-app-layout>
