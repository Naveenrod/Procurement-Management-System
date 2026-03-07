<x-app-layout>
    <x-slot name="title">Inventory Report</x-slot>
    <div class="py-6 space-y-5 w-full">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Inventory Report</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Stock levels, warehouse distribution, and low stock alerts</p>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Total SKUs --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total SKUs</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($data['total_skus'] ?? 0) }}</p>
                </div>
            </div>
            {{-- Total Stock Value --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Stock Value</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($data['total_value'] ?? 0, 0) }}</p>
                </div>
            </div>
            {{-- Low Stock Items --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-red-50 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Low Stock Items</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($data['low_stock'] ?? 0) }}</p>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        @if(isset($data['by_warehouse']) && count($data['by_warehouse']))
        <div class="grid grid-cols-1 xl:grid-cols-[2fr_1fr] gap-5">
            {{-- Stock Value by Warehouse Bar --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Stock Value by Warehouse</h3>
                <div class="relative" style="height:260px;">
                    <canvas id="invWarehouseValueChart"></canvas>
                </div>
            </div>

            {{-- Quantity Distribution Doughnut --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Quantity Distribution</h3>
                <div class="relative" style="height:260px;">
                    <canvas id="invWarehouseDistChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        {{-- Warehouse Summary Table --}}
        @if(isset($data['by_warehouse']) && count($data['by_warehouse']))
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Stock by Warehouse</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Warehouse</th>
                            <th class="px-4 py-3 text-right">Total Items</th>
                            <th class="px-4 py-3 text-right">Est. Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($data['by_warehouse'] as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row->warehouse_name }}</td>
                            <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">{{ number_format($row->total_quantity, 0) }}</td>
                            <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">${{ number_format($row->total_value ?? 0, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Total</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">{{ number_format(collect($data['by_warehouse'])->sum('total_quantity'), 0) }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">${{ number_format(collect($data['by_warehouse'])->sum('total_value'), 0) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        {{-- Low Stock Alert Table --}}
        @if(isset($data['low_stock_items']) && count($data['low_stock_items']))
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h3 class="font-semibold text-gray-900 dark:text-white">Low Stock Items (Top 10)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Product</th>
                            <th class="px-4 py-3 text-left">SKU</th>
                            <th class="px-4 py-3 text-left">Warehouse</th>
                            <th class="px-4 py-3 text-right">On Hand</th>
                            <th class="px-4 py-3 text-right">Reorder Point</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($data['low_stock_items'] as $item)
                        <tr class="bg-red-50/50 dark:bg-red-900/10 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $item->product_name }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 font-mono text-xs">{{ $item->sku }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $item->warehouse_name }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-red-600 dark:text-red-400">{{ number_format($item->quantity_on_hand, 0) }}</td>
                            <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400">{{ number_format($item->reorder_point, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>

    @if(isset($data['by_warehouse']) && count($data['by_warehouse']))
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var isDark = document.documentElement.classList.contains('dark');
        var gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
        var textColor = isDark ? '#9ca3af' : '#6b7280';
        var chartColors = ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'];

        function fmtMoney(v) { return '$' + (v >= 1000 ? (v/1000).toFixed(1) + 'k' : v.toLocaleString()); }

        var warehouseLabels = @json(collect($data['by_warehouse'])->pluck('warehouse_name'));
        var warehouseValues = @json(collect($data['by_warehouse'])->pluck('total_value'));
        var warehouseQuantities = @json(collect($data['by_warehouse'])->pluck('total_quantity'));

        new Chart(document.getElementById('invWarehouseValueChart'), {
            type: 'bar',
            data: {
                labels: warehouseLabels,
                datasets: [{
                    label: 'Stock Value',
                    data: warehouseValues,
                    backgroundColor: chartColors.slice(0, warehouseLabels.length),
                    borderWidth: 0,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: fmtMoney, color: textColor, font: { size: 11 } }, grid: { color: gridColor } },
                    x: { ticks: { color: textColor, font: { size: 11 } }, grid: { display: false } }
                }
            }
        });

        new Chart(document.getElementById('invWarehouseDistChart'), {
            type: 'doughnut',
            data: {
                labels: warehouseLabels,
                datasets: [{
                    data: warehouseQuantities,
                    backgroundColor: chartColors.slice(0, warehouseLabels.length),
                    borderWidth: 0,
                    cutout: '72%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 10, padding: 10, font: { size: 11 }, color: textColor } },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                var total = ctx.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                var pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : '0.0';
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
