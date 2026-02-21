<x-app-layout>
    <x-slot name="title">Picking</x-slot>
    <div class="py-6">
        @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        @if($orders->count())
        <div class="mb-4 px-4 py-2 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm font-medium text-yellow-800">{{ $orders->count() }} {{ Str::plural('order', $orders->count()) }} ready for picking</p>
        </div>

        @foreach($orders as $order)
        @php
            $pendingItems = $order->items->where('status', '!=', 'picked');
            $pickedItems  = $order->items->where('status', 'picked');
            $allPicked    = $pendingItems->isEmpty();
        @endphp

        <div class="bg-white rounded-lg shadow-sm border overflow-hidden mb-6">
            {{-- Order header --}}
            <div class="px-5 py-3 border-b {{ $allPicked ? 'bg-green-50' : 'bg-yellow-50' }} flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="font-mono font-bold text-sm text-gray-800">{{ $order->order_number }}</span>
                    <span class="text-gray-500 text-sm">{{ optional($order->warehouse)->name }}</span>
                </div>
                <div class="flex items-center gap-2">
                    @if($allPicked)
                        <span class="text-xs text-green-700 bg-green-100 px-2 py-1 rounded-full font-medium">All items picked</span>
                    @else
                        <span class="text-xs text-yellow-700 bg-yellow-100 px-2 py-1 rounded-full font-medium">
                            {{ $pickedItems->count() }}/{{ $order->items->count() }} picked
                        </span>
                    @endif
                </div>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-left">Expected SKU</th>
                        <th class="px-4 py-3 text-left">Location</th>
                        <th class="px-4 py-3 text-right">Expected Qty</th>
                        <th class="px-4 py-3 text-left">Scan / Enter SKU</th>
                        <th class="px-4 py-3 text-right w-28">Pick Qty</th>
                        <th class="px-4 py-3 text-center w-24">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    {{-- Already-picked items (read-only) --}}
                    @foreach($pickedItems as $item)
                    <tr class="bg-green-50 opacity-75">
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ $item->product->name }}
                            <div class="text-xs text-gray-400">{{ $item->product->unit_of_measure }}</div>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $item->product->sku }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ optional($item->location)->code ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-gray-500">{{ number_format($item->expected_quantity, 0) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 font-mono text-xs text-green-700 bg-green-100 px-2 py-1 rounded">
                                {{ $item->product->sku }} ✓
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-green-700">{{ number_format($item->picked_quantity, 0) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs text-green-700 bg-green-100 px-2 py-0.5 rounded-full font-medium">Picked</span>
                        </td>
                    </tr>
                    @endforeach

                    {{-- Pending items (editable form rows) --}}
                    @if($pendingItems->isNotEmpty())
                    <form method="POST" action="{{ route('warehouse.picking.process', $order) }}" id="pick-form-{{ $order->id }}">
                        @csrf
                        @foreach($pendingItems->values() as $i => $item)
                        <tr x-data="{ scanned: '{{ old("items.{$i}.sku", '') }}', get valid() { return this.scanned === '' ? null : this.scanned.trim().toUpperCase() === '{{ strtoupper($item->product->sku) }}' } }"
                            :class="valid === false ? 'bg-red-50' : (valid === true ? 'bg-yellow-50' : 'hover:bg-gray-50')">
                            <input type="hidden" name="items[{{ $i }}][item_id]" value="{{ $item->id }}">

                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $item->product->name }}
                                <div class="text-xs text-gray-400">{{ $item->product->unit_of_measure }}</div>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs font-semibold text-gray-600">{{ $item->product->sku }}</td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ optional($item->location)->code ?? '—' }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-700">{{ number_format($item->expected_quantity, 0) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <input type="text"
                                           name="items[{{ $i }}][sku]"
                                           x-model="scanned"
                                           placeholder="Scan or type SKU…"
                                           autocomplete="off"
                                           @if($i === 0) autofocus @endif
                                           class="border rounded-md px-3 py-1.5 text-sm font-mono w-44 transition-colors"
                                           :class="valid === null ? 'border-gray-300' : (valid ? 'border-green-500 bg-green-50 text-green-800' : 'border-red-400 bg-red-50 text-red-800')">
                                    <span x-show="valid === true" class="text-green-600 text-base leading-none">✓</span>
                                    <span x-show="valid === false" class="text-red-500 text-xs whitespace-nowrap">SKU mismatch</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <input type="number"
                                       name="items[{{ $i }}][picked_quantity]"
                                       value="{{ old("items.{$i}.picked_quantity", $item->expected_quantity) }}"
                                       min="0" max="{{ $item->expected_quantity }}"
                                       class="border rounded-md px-2 py-1.5 text-sm text-right w-20">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded-full font-medium">Pending</span>
                            </td>
                        </tr>
                        @endforeach
                    </form>
                    @endif
                </tbody>
            </table>

            <div class="px-5 py-3 bg-gray-50 border-t flex items-center justify-between">
                @if($allPicked)
                    <p class="text-sm font-medium text-green-700">All items confirmed — this order will appear in Packing.</p>
                @else
                    <p class="text-xs text-gray-500">Scan each SKU to verify, then confirm the quantity picked.</p>
                    <button type="submit" form="pick-form-{{ $order->id }}"
                            class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-md transition-colors">
                        Confirm Pick List
                    </button>
                @endif
            </div>
        </div>
        @endforeach

        @else
        <x-empty-state title="No orders ready for picking" description="Outbound warehouse orders with pending status will appear here." />
        @endif
    </div>
</x-app-layout>
