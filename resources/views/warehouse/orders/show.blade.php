<x-app-layout>
    <x-slot name="title">{{ $order->order_number }}</x-slot>
    <div class="py-6 max-w-4xl space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $order->order_number }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ optional($order->warehouse)->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-status-badge :status="$order->type" />
                    <x-status-badge :status="$order->status" />
                </div>
            </div>
            @if($order->notes)<p class="mt-3 text-sm text-gray-600">{{ $order->notes }}</p>@endif
        </div>
        @if($order->items->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Items</h3></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Product</th><th class="px-4 py-3 text-right">Expected</th><th class="px-4 py-3 text-right">Received</th><th class="px-4 py-3 text-right">Picked</th><th class="px-4 py-3 text-left">Status</th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($order->items as $item)
                    <tr><td class="px-4 py-3">{{ optional($item->product)->name }}</td><td class="px-4 py-3 text-right">{{ $item->expected_quantity }}</td><td class="px-4 py-3 text-right">{{ $item->received_quantity }}</td><td class="px-4 py-3 text-right">{{ $item->picked_quantity }}</td><td class="px-4 py-3"><x-status-badge :status="$item->status" /></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>
