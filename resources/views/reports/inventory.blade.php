<x-app-layout>
    <x-slot name="title">Inventory Report</x-slot>
    <div class="py-6 space-y-6">
        <div class="grid grid-cols-3 gap-4">
            <x-stats-card title="Total SKUs" :value="$data['total_skus'] ?? 0" icon="📋" color="blue" />
            <x-stats-card title="Total Stock Value" :value="'$'.number_format($data['total_value'] ?? 0, 0)" icon="💰" color="green" />
            <x-stats-card title="Low Stock Items" :value="$data['low_stock'] ?? 0" icon="⚠️" color="red" />
        </div>
        @if(isset($data['by_warehouse']))
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Stock by Warehouse</h3></div>
            <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Warehouse</th><th class="px-4 py-3 text-right">Total Items</th><th class="px-4 py-3 text-right">Est. Value</th></tr></thead>
            <tbody class="divide-y">@foreach($data['by_warehouse'] as $row)<tr><td class="px-4 py-3">{{ $row->warehouse_name }}</td><td class="px-4 py-3 text-right">{{ number_format($row->total_quantity, 0) }}</td><td class="px-4 py-3 text-right">${{ number_format($row->total_value ?? 0, 0) }}</td></tr>@endforeach</tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>
