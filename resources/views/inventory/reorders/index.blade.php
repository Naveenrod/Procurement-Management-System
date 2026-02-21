<x-app-layout>
    <x-slot name="title">Reorder Alerts</x-slot>
    <div class="py-6">
        @if(count($alerts))
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">SKU</th>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-left">Warehouse</th>
                        <th class="px-4 py-3 text-right">Current Stock</th>
                        <th class="px-4 py-3 text-right">Reorder Point</th>
                        <th class="px-4 py-3 text-right">Deficit</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($alerts as $alert)
                    <tr class="bg-red-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $alert['sku'] }}</td>
                        <td class="px-4 py-3 font-medium text-red-800">{{ $alert['product'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $alert['warehouse'] }}</td>
                        <td class="px-4 py-3 text-right text-red-600 font-bold">{{ number_format($alert['current_stock'], 2) }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($alert['reorder_point'], 2) }}</td>
                        <td class="px-4 py-3 text-right font-bold text-red-700">{{ number_format($alert['deficit'], 2) }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('procurement.purchase-orders.create', ['product_id' => $alert['product_id']]) }}" class="px-2 py-1 bg-blue-600 text-white text-xs rounded">Create PO</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <x-empty-state title="No reorder alerts" description="All products are above their reorder points." />
        @endif
    </div>
</x-app-layout>
