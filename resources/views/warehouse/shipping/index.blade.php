<x-app-layout>
    <x-slot name="title">Shipping</x-slot>
    <div class="py-6">
        @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
        @endif

        @if($orders->count())
        @php
            $readyCount = $orders->where('status->value', 'packing')->count() ?: $orders->filter(fn($o) => $o->getRawOriginal('status') === 'packing')->count();
        @endphp
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-3 border-b bg-gray-50 flex items-center justify-between">
                <p class="text-sm font-medium text-gray-700">Shipping Queue</p>
                <span class="text-xs text-gray-500">Newest first</span>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Order #</th>
                        <th class="px-4 py-3 text-left">Warehouse</th>
                        <th class="px-4 py-3 text-center">Items</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Notes / Tracking</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($orders as $order)
                    @php $shipped = $order->getRawOriginal('status') === 'shipped'; @endphp
                    <tr class="{{ $shipped ? 'bg-gray-50 opacity-80' : 'bg-white hover:bg-blue-50' }}">
                        <td class="px-4 py-3 font-mono text-xs font-semibold text-gray-700">{{ $order->order_number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ optional($order->warehouse)->name }}</td>
                        <td class="px-4 py-3 text-center text-gray-600">{{ $order->items->count() }}</td>
                        <td class="px-4 py-3">
                            @if($shipped)
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded-full">
                                    ✓ Shipped
                                </span>
                            @else
                                <span class="inline-flex items-center text-xs font-medium text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">
                                    Ready to Ship
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $order->notes ? \Illuminate\Support\Str::limit($order->notes, 40) : '—' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-400">{{ $order->updated_at->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3">
                            @if(!$shipped)
                            <form method="POST" action="{{ route('warehouse.shipping.process', $order) }}" class="flex gap-2">
                                @csrf
                                <input type="text" name="tracking_number" placeholder="Tracking # (optional)"
                                       class="border rounded-md px-2 py-1.5 text-xs w-40">
                                <button class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-md whitespace-nowrap">
                                    Mark Shipped
                                </button>
                            </form>
                            @else
                            <span class="text-xs text-gray-400 italic">Completed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <x-empty-state title="No orders in shipping queue" description="Orders ready for shipping will appear here after packing is complete." />
        @endif
    </div>
</x-app-layout>
