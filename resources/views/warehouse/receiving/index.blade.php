<x-app-layout>
    <x-slot name="title">Receiving</x-slot>
    <div class="py-6">
        @if($orders->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b bg-blue-50">
                <p class="text-sm font-medium text-blue-800">{{ $orders->count() }} inbound orders pending receipt</p>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Order #</th><th class="px-4 py-3 text-left">Warehouse</th><th class="px-4 py-3 text-left">Items</th><th class="px-4 py-3 text-left">Created</th><th class="px-4 py-3"></th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $order->order_number }}</td>
                        <td class="px-4 py-3">{{ optional($order->warehouse)->name }}</td>
                        <td class="px-4 py-3">{{ $order->items->count() }} items</td>
                        <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('warehouse.receiving.receive', $order) }}">@csrf
                                <button class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-md">Process Receipt</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <x-empty-state title="No pending inbound orders" description="All inbound orders have been processed." />
        @endif
    </div>
</x-app-layout>
