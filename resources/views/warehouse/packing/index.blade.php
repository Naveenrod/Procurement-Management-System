<x-app-layout>
    <x-slot name="title">Packing</x-slot>
    <div class="py-6">
        @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        @if($orders->count())
        <div class="mb-4 px-4 py-2 bg-orange-50 dark:bg-orange-900/30 border border-orange-200 dark:border-orange-700 rounded-lg">
            <p class="text-sm font-medium text-orange-800 dark:text-orange-300">{{ $orders->count() }} {{ Str::plural('order', $orders->count()) }} ready for packing</p>
        </div>

        @foreach($orders as $order)
        @php
            $unpackedItems = $order->items->where('status', 'picked');
            $packedItems   = $order->items->where('status', 'packed');
            $allPacked     = $unpackedItems->isEmpty();
        @endphp

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            {{-- Order header --}}
            <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 {{ $allPacked ? 'bg-green-50 dark:bg-green-900/20' : 'bg-orange-50 dark:bg-orange-900/20' }} flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="font-mono font-bold text-sm text-gray-800 dark:text-gray-200">{{ $order->order_number }}</span>
                    <span class="text-gray-500 dark:text-gray-400 text-sm">{{ optional($order->warehouse)->name }}</span>
                </div>
                <div class="flex items-center gap-2">
                    @if($allPacked)
                        <span class="text-xs text-green-700 bg-green-100 dark:bg-green-900/40 dark:text-green-300 px-2 py-1 rounded-full font-medium">All items packed</span>
                    @else
                        <span class="text-xs text-orange-700 bg-orange-100 dark:bg-orange-900/40 dark:text-orange-300 px-2 py-1 rounded-full font-medium">
                            {{ $packedItems->count() }}/{{ $order->items->count() }} packed
                        </span>
                    @endif
                </div>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-left">Expected SKU</th>
                        <th class="px-4 py-3 text-right">Qty to Pack</th>
                        <th class="px-4 py-3 text-left">Scan / Enter SKU</th>
                        <th class="px-4 py-3 text-center w-24">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                    {{-- Already-packed items (read-only) --}}
                    @foreach($packedItems as $item)
                    <tr class="bg-green-50 dark:bg-green-900/10 opacity-75">
                        <td class="px-4 py-3 font-medium text-gray-700 dark:text-gray-300">
                            {{ $item->product->name }}
                            <div class="text-xs text-gray-400">{{ $item->product->unit_of_measure }}</div>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $item->product->sku }}</td>
                        <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400">{{ number_format($item->picked_quantity, 0) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 font-mono text-xs text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/40 px-2 py-1 rounded">
                                {{ $item->product->sku }} ✓
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/40 px-2 py-0.5 rounded-full font-medium">Packed</span>
                        </td>
                    </tr>
                    @endforeach

                    {{-- Items to pack (editable form rows) --}}
                    @if($unpackedItems->isNotEmpty())
                    <form method="POST" action="{{ route('warehouse.packing.process', $order) }}" id="pack-form-{{ $order->id }}">
                        @csrf
                        @foreach($unpackedItems->values() as $i => $item)
                        <tr x-data="{ scanned: '{{ old("items.{$i}.sku", '') }}', get valid() { return this.scanned === '' ? null : this.scanned.trim().toUpperCase() === '{{ strtoupper($item->product->sku) }}' } }"
                            :class="valid === false ? 'bg-red-50 dark:bg-red-900/10' : (valid === true ? 'bg-orange-50 dark:bg-orange-900/10' : 'hover:bg-gray-50 dark:hover:bg-gray-700/30')">
                            <input type="hidden" name="items[{{ $i }}][item_id]" value="{{ $item->id }}">

                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-200">
                                {{ $item->product->name }}
                                <div class="text-xs text-gray-400">{{ $item->product->unit_of_measure }}</div>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs font-semibold text-gray-600 dark:text-gray-400">{{ $item->product->sku }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">
                                {{ number_format($item->picked_quantity, 0) }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <input type="text"
                                           name="items[{{ $i }}][sku]"
                                           x-model="scanned"
                                           placeholder="Scan or type SKU…"
                                           autocomplete="off"
                                           @if($i === 0) autofocus @endif
                                           class="border rounded-md px-3 py-1.5 text-sm font-mono w-44 transition-colors dark:bg-gray-700 dark:text-gray-100"
                                           :class="valid === null ? 'border-gray-300 dark:border-gray-600' : (valid ? 'border-green-500 bg-green-50 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'border-red-400 bg-red-50 dark:bg-red-900/30 text-red-800 dark:text-red-300')">
                                    <span x-show="valid === true" class="text-green-600 dark:text-green-400 text-base leading-none">✓</span>
                                    <span x-show="valid === false" class="text-red-500 text-xs whitespace-nowrap">SKU mismatch</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs text-orange-700 dark:text-orange-400 bg-orange-100 dark:bg-orange-900/40 px-2 py-0.5 rounded-full font-medium">Picked</span>
                            </td>
                        </tr>
                        @endforeach
                    </form>
                    @endif
                </tbody>
            </table>

            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                @if($allPacked)
                    <p class="text-sm font-medium text-green-700 dark:text-green-400">All items packed — this order will appear in Shipping.</p>
                @else
                    <p class="text-xs text-gray-500 dark:text-gray-400">Scan each item's SKU to confirm it's going into the right box, then confirm.</p>
                    <button type="submit" form="pack-form-{{ $order->id }}"
                            class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-md transition-colors">
                        Confirm Pack
                    </button>
                @endif
            </div>
        </div>
        @endforeach

        @else
        <x-empty-state title="No orders ready for packing" description="Orders that have completed picking will appear here." />
        @endif
    </div>
</x-app-layout>
