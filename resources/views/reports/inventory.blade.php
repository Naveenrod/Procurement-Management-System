<x-app-layout>
    <x-slot name="title">Inventory Report</x-slot>
    <div class="py-6 space-y-6">
        <div class="grid grid-cols-3 gap-4">
            <x-stats-card title="Total SKUs" :value="$data['total_skus'] ?? 0" icon="📋" color="blue" />
            <x-stats-card title="Total Stock Value" :value="'$'.number_format($data['total_value'] ?? 0, 0)" icon="💰" color="green" />
            <x-stats-card title="Low Stock Items" :value="$data['low_stock'] ?? 0" icon="⚠️" color="red" />
        </div>

        {{-- Charts Section --}}
        @if(isset($data['by_warehouse']) && count($data['by_warehouse']))
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Stock Value by Warehouse</h3>
                <div style="position: relative; height: 280px;">
                    <canvas id="invWarehouseValueChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Stock Distribution by Warehouse</h3>
                <div style="position: relative; height: 280px;">
                    <canvas id="invWarehouseDistChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        @if(isset($data['by_warehouse']) && count($data['by_warehouse']))
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Stock by Warehouse</h3></div>
            <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Warehouse</th><th class="px-4 py-3 text-right">Total Items</th><th class="px-4 py-3 text-right">Est. Value</th></tr></thead>
            <tbody class="divide-y">@foreach($data['by_warehouse'] as $row)<tr><td class="px-4 py-3">{{ $row->warehouse_name }}</td><td class="px-4 py-3 text-right">{{ number_format($row->total_quantity, 0) }}</td><td class="px-4 py-3 text-right">${{ number_format($row->total_value ?? 0, 0) }}</td></tr>@endforeach</tbody>
            </table>
        </div>
        @endif

        @if(isset($data['low_stock_items']) && count($data['low_stock_items']))
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Low Stock Items (Top 10)</h3></div>
            <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Product</th><th class="px-4 py-3 text-left">SKU</th><th class="px-4 py-3 text-left">Warehouse</th><th class="px-4 py-3 text-right">On Hand</th><th class="px-4 py-3 text-right">Reorder Point</th></tr></thead>
            <tbody class="divide-y">@foreach($data['low_stock_items'] as $item)<tr class="bg-red-50"><td class="px-4 py-3 font-medium">{{ $item->product_name }}</td><td class="px-4 py-3 text-gray-500">{{ $item->sku }}</td><td class="px-4 py-3">{{ $item->warehouse_name }}</td><td class="px-4 py-3 text-right text-red-600 font-semibold">{{ number_format($item->quantity_on_hand, 0) }}</td><td class="px-4 py-3 text-right">{{ number_format($item->reorder_point, 0) }}</td></tr>@endforeach</tbody>
            </table>
        </div>
        @endif
    </div>

    @if(isset($data['by_warehouse']) && count($data['by_warehouse']))
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var chartColors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'];
        var warehouseLabels = @json(collect($data['by_warehouse'])->pluck('warehouse_name'));
        var warehouseValues = @json(collect($data['by_warehouse'])->pluck('total_value'));
        var warehouseQuantities = @json(collect($data['by_warehouse'])->pluck('total_quantity'));

        new Chart(document.getElementById('invWarehouseValueChart'), {
            type: 'bar',
            data: {
                labels: warehouseLabels,
                datasets: [{
                    label: 'Stock Value ($)',
                    data: warehouseValues,
                    backgroundColor: chartColors.slice(0, warehouseLabels.length),
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: function(v) { return '$' + v.toLocaleString(); } }, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        new Chart(document.getElementById('invWarehouseDistChart'), {
            type: 'pie',
            data: {
                labels: warehouseLabels,
                datasets: [{
                    data: warehouseQuantities,
                    backgroundColor: chartColors.slice(0, warehouseLabels.length),
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 12, padding: 10, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                var total = ctx.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                var pct = ((ctx.parsed / total) * 100).toFixed(1);
                                return ctx.label + ': ' + ctx.parsed.toLocaleString() + ' units (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    });
    </script>
    @endpush
    @endif
</x-app-layout>
