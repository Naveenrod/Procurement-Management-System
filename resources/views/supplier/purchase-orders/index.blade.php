@extends('layouts.supplier')
@section('title', 'My Purchase Orders')
@section('content')
<div class="py-6">
    @if($orders->count())
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3 text-left">PO Number</th><th class="px-4 py-3 text-left">Order Date</th><th class="px-4 py-3 text-left">Expected Delivery</th><th class="px-4 py-3 text-right">Total</th><th class="px-4 py-3 text-left">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs font-semibold">{{ $order->po_number }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ optional($order->order_date)->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ optional($order->expected_delivery_date)->format('M d, Y') ?? '—' }}</td>
                    <td class="px-4 py-3 text-right font-medium">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$order->status" /></td>
                    <td class="px-4 py-3"><a href="{{ route('supplier.purchase-orders.show', $order) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
    @else
    <x-empty-state title="No purchase orders" description="Purchase orders assigned to your account will appear here." />
    @endif
</div>
@endsection
