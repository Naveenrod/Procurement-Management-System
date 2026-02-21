<x-app-layout>
    <x-slot name="title">Shipping</x-slot>
    <div class="py-6">
        @if($orders->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Order #</th><th class="px-4 py-3 text-left">Warehouse</th><th class="px-4 py-3 text-left">Items</th><th class="px-4 py-3"></th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $order->order_number }}</td>
                        <td class="px-4 py-3">{{ optional($order->warehouse)->name }}</td>
                        <td class="px-4 py-3">{{ $order->items->count() }} items</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('warehouse.shipping.ship', $order) }}" class="flex gap-2">
                                @csrf
                                <input type="text" name="tracking_number" placeholder="Tracking #" class="border rounded-md px-2 py-1.5 text-xs">
                                <button class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-md">Ship</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <x-empty-state title="No orders ready to ship" />
        @endif
    </div>
</x-app-layout>
