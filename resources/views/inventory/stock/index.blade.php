<x-app-layout>
    <x-slot name="title">Stock Levels</x-slot>
    <div class="py-6">
        <div class="flex justify-between items-center mb-4">
            <form method="GET" class="flex gap-2">
                <select name="warehouse_id" class="border rounded-md px-3 py-1.5 text-sm">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses as $wh)
                    <option value="{{ $wh->id }}" @selected(request('warehouse_id') == $wh->id)>{{ $wh->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search SKU, name..." class="border rounded-md px-3 py-1.5 text-sm">
                <button class="px-3 py-1.5 bg-gray-100 rounded-md text-sm">Filter</button>
            </form>
            <a href="{{ route('inventory.stock.adjust') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Adjust Stock</a>
        </div>
        @if($inventory->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">SKU</th>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-left">Warehouse</th>
                        <th class="px-4 py-3 text-right">On Hand</th>
                        <th class="px-4 py-3 text-right">Reserved</th>
                        <th class="px-4 py-3 text-right">Available</th>
                        <th class="px-4 py-3 text-right">Reorder Pt.</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($inventory as $item)
                    @php $low = optional($item->product)->reorder_point && $item->quantity_on_hand <= $item->product->reorder_point; @endphp
                    <tr class="{{ $low ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                        <td class="px-4 py-3 font-mono text-xs">{{ optional($item->product)->sku }}</td>
                        <td class="px-4 py-3 font-medium {{ $low ? 'text-red-700' : 'text-gray-800' }}">{{ optional($item->product)->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($item->warehouse)->name }}</td>
                        <td class="px-4 py-3 text-right {{ $low ? 'text-red-600 font-bold' : '' }}">{{ number_format($item->quantity_on_hand, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-500">{{ number_format($item->quantity_reserved, 2) }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ number_format($item->quantity_on_hand - $item->quantity_reserved, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-400">{{ optional($item->product)->reorder_point ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $inventory->withQueryString()->links() }}</div>
        @else
        <x-empty-state title="No inventory records" description="Add warehouses and receive goods to populate inventory." />
        @endif
    </div>
</x-app-layout>
