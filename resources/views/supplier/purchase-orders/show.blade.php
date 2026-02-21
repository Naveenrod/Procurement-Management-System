@extends('layouts.supplier')
@section('title', $order->po_number)
@section('content')
<div class="py-6 max-w-4xl space-y-4">
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $order->po_number }}</h2>
                <p class="text-sm text-gray-500 mt-1">Order Date: {{ optional($order->order_date)->format('M d, Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <x-status-badge :status="$order->status" />
                @if($order->status === 'sent')
                <form method="POST" action="{{ route('supplier.purchase-orders.acknowledge', $order) }}">@csrf
                    <button class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md">Acknowledge</button>
                </form>
                @endif
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
            <div><p class="text-gray-500">Expected Delivery</p><p class="font-medium">{{ optional($order->expected_delivery_date)->format('M d, Y') ?? '—' }}</p></div>
            <div><p class="text-gray-500">Total Amount</p><p class="font-bold text-lg">${{ number_format($order->total_amount, 2) }}</p></div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Items</h3></div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Product</th><th class="px-4 py-3 text-right">Qty</th><th class="px-4 py-3 text-right">Unit Price</th><th class="px-4 py-3 text-right">Total</th></tr></thead>
            <tbody class="divide-y">
                @foreach($order->items as $item)
                <tr><td class="px-4 py-3">{{ optional($item->product)->name }}</td><td class="px-4 py-3 text-right">{{ $item->quantity }}</td><td class="px-4 py-3 text-right">${{ number_format($item->unit_price, 2) }}</td><td class="px-4 py-3 text-right font-medium">${{ number_format($item->total_price, 2) }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
