<x-app-layout>
    <x-slot name="title">Procurement Report</x-slot>
    <div class="py-6 space-y-5 w-full">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Procurement Report</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Purchase order spend analysis and vendor breakdown</p>
            </div>
        </div>

        {{-- Filter Bar --}}
        <form method="GET" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-4 flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">From</label>
                <input type="date" name="from" value="{{ request('from', now()->startOfYear()->format('Y-m-d')) }}"
                    class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">To</label>
                <input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}"
                    class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                Apply Filter
            </button>
        </form>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Total POs --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total POs</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($data['total_pos'] ?? 0) }}</p>
                </div>
            </div>
            {{-- Total Spend --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Spend</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($data['total_spend'] ?? 0, 0) }}</p>
                </div>
            </div>
            {{-- Avg PO Value --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Avg PO Value</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format(($data['total_pos'] ?? 0) > 0 ? ($data['total_spend'] ?? 0) / $data['total_pos'] : 0, 0) }}</p>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 xl:grid-cols-[2fr_1fr] gap-5">
            {{-- Monthly Spend Trend --}}
            @if(isset($spendByMonth) && count($spendByMonth))
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Monthly Spend Trend</h3>
                <div class="relative" style="height:260px;">
                    <canvas id="procMonthlySpendChart"></canvas>
                </div>
            </div>
            @endif

            {{-- Spend by Category --}}
            @if(isset($spendByCategory) && count($spendByCategory))
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Spend by Category</h3>
                <div class="relative" style="height:260px;">
                    <canvas id="procCategoryChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        {{-- Vendor Horizontal Bar + Table --}}
        @if(isset($spendByVendor) && count($spendByVendor))
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_1fr] gap-5">
            {{-- Vendor Bar Chart --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Top Vendors by Spend</h3>
                <div class="relative" style="height:300px;">
                    <canvas id="procVendorChart"></canvas>
                </div>
            </div>

            {{-- Vendor Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Spend by Vendor</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left">Vendor</th>
                                <th class="px-4 py-3 text-right">Total Spend</th>
                                <th class="px-4 py-3 text-right">PO Count</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($spendByVendor as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row->vendor_name }}</td>
                                <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">${{ number_format($row->total, 0) }}</td>
                                <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400">{{ $row->count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white text-sm">Total</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">${{ number_format($spendByVendor->sum('total'), 0) }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">{{ $spendByVendor->sum('count') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var isDark = document.documentElement.classList.contains('dark');
        var gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
        var textColor = isDark ? '#9ca3af' : '#6b7280';
        var chartColors = ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'];

        function fmtMoney(v) { return '$' + (v >= 1000 ? (v/1000).toFixed(1) + 'k' : v.toLocaleString()); }

        @if(isset($spendByMonth) && count($spendByMonth))
        new Chart(document.getElementById('procMonthlySpendChart'), {
            type: 'bar',
            data: {
                labels: @json(collect($spendByMonth)->pluck('month')),
                datasets: [{
                    label: 'Monthly Spend',
                    data: @json(collect($spendByMonth)->pluck('total')),
                    backgroundColor: 'rgba(99,102,241,0.75)',
                    borderColor: '#6366f1',
                    borderWidth: 1,
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
        @endif

        @if(isset($spendByCategory) && count($spendByCategory))
        new Chart(document.getElementById('procCategoryChart'), {
            type: 'doughnut',
            data: {
                labels: @json(collect($spendByCategory)->pluck('name')),
                datasets: [{
                    data: @json(collect($spendByCategory)->pluck('total')),
                    backgroundColor: chartColors.slice(0, {{ count($spendByCategory) }}),
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
                                return ctx.label + ': $' + ctx.parsed.toLocaleString() + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
        @endif

        @if(isset($spendByVendor) && count($spendByVendor))
        new Chart(document.getElementById('procVendorChart'), {
            type: 'bar',
            data: {
                labels: @json(collect($spendByVendor)->pluck('vendor_name')),
                datasets: [{
                    label: 'Total Spend',
                    data: @json(collect($spendByVendor)->pluck('total')),
                    backgroundColor: chartColors.slice(0, {{ count($spendByVendor) }}),
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { callback: fmtMoney, color: textColor, font: { size: 11 } }, grid: { color: gridColor } },
                    y: { ticks: { color: textColor, font: { size: 11 } }, grid: { display: false } }
                }
            }
        });
        @endif
    });
    </script>
    @endpush
</x-app-layout>
