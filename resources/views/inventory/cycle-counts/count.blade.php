<x-app-layout>
    <x-slot name="title">Count — {{ $cycleCount->count_number }}</x-slot>
    <div class="py-6 max-w-4xl">
        <form method="POST" action="{{ route('inventory.cycle-counts.count', $cycleCount) }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-4">
                <h2 class="text-lg font-bold text-gray-800 mb-1">{{ $cycleCount->count_number }}</h2>
                <p class="text-sm text-gray-500">{{ optional($cycleCount->warehouse)->name }} — Enter counted quantities for each item</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">Product</th>
                            <th class="px-4 py-3 text-left">Location</th>
                            <th class="px-4 py-3 text-right">System Qty</th>
                            <th class="px-4 py-3 text-right">Counted Qty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($cycleCount->items as $index => $item)
                        <tr>
                            <td class="px-4 py-3">{{ optional($item->product)->name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ optional($item->location)->zone ?? '—' }}</td>
                            <td class="px-4 py-3 text-right font-medium">{{ number_format($item->system_quantity, 0) }}</td>
                            <td class="px-4 py-3 text-right">
                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                <input type="number" name="items[{{ $index }}][counted_quantity]" value="{{ old("items.{$index}.counted_quantity", $item->counted_quantity) }}" min="0" step="1" required class="w-24 border rounded-md px-2 py-1 text-sm text-right">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('inventory.cycle-counts.show', $cycleCount) }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save Count</button>
            </div>
        </form>
    </div>
</x-app-layout>
