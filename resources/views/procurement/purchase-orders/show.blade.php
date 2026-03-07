<x-app-layout>
    <x-slot name="title">{{ $order->po_number }}</x-slot>
    <div class="py-6 max-w-5xl space-y-4" x-data="{ rejectOpen: false }">

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $order->po_number }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ optional($order->vendor)->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('procurement.purchase-orders.pdf', $order) }}"
                       class="px-3 py-1.5 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700">
                        Download PDF
                    </a>
                    <x-status-badge :status="$order->status" />

                    @if(in_array($order->status?->value, ['draft', 'pending_approval']) && auth()->user()->hasRole(['admin', 'manager']))
                    <form method="POST" action="{{ route('procurement.purchase-orders.approve', $order) }}">@csrf
                        <button class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">Approve</button>
                    </form>
                    @endif

                    @if($order->status?->value === 'approved')
                    <form method="POST" action="{{ route('procurement.purchase-orders.send', $order) }}">@csrf
                        <button class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Send to Vendor</button>
                    </form>
                    @endif

                    @if(in_array($order->status?->value, ['sent', 'approved']))
                    <a href="{{ route('procurement.goods-receipts.create', ['po_id' => $order->id]) }}" class="px-3 py-1.5 bg-purple-600 text-white text-sm rounded-md hover:bg-purple-700">Receive Goods</a>
                    @endif

                    @if(in_array($order->status?->value, ['draft', 'pending_approval', 'approved']) && auth()->user()->hasRole(['admin', 'manager']))
                    <button @click="rejectOpen = true"
                            class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                        Reject
                    </button>
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

        {{-- Rejection notice --}}
        @if($order->status?->value === 'rejected')
        <div class="bg-red-50 border border-red-200 rounded-lg p-5">
            <div class="flex items-start gap-3">
                <div class="text-red-500 text-xl leading-none mt-0.5">✕</div>
                <div class="flex-1">
                    <p class="font-semibold text-red-800">Purchase Order Rejected</p>
                    <p class="text-sm text-red-700 mt-1">{{ $order->rejection_reason }}</p>
                    <p class="text-xs text-red-500 mt-2">
                        Rejected by {{ optional($order->rejecter)->name ?? '—' }}
                        @if($order->rejected_at) · {{ $order->rejected_at->format('M d, Y H:i') }} @endif
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Reject modal --}}
        <div x-show="rejectOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
             @keydown.escape.window="rejectOpen = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6" @click.stop>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Reject Purchase Order</h3>
                <form method="POST" action="{{ route('procurement.purchase-orders.reject', $order) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason for rejection <span class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" rows="4" required
                                  placeholder="Explain why this PO is being rejected…"
                                  class="w-full border rounded-md px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">{{ old('rejection_reason') }}</textarea>
                        <x-input-error :messages="$errors->get('rejection_reason')" class="mt-1" />
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="rejectOpen = false"
                                class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">Confirm Rejection</button>
                    </div>
                </form>
            </div>
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
                <a href="{{ route('procurement.goods-receipts.show', $gr) }}" class="text-blue-600 hover:underline text-sm">{{ $gr->receipt_number }}</a>
                <x-status-badge :status="$gr->status" />
                <span class="text-xs text-gray-500">{{ optional($gr->received_at)->format('M d, Y') }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</x-app-layout>
