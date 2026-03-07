<x-app-layout>
    <x-slot name="title">Vendor Report</x-slot>
    <div class="py-6 space-y-5 w-full">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Vendor Report</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Vendor status breakdown and performance analysis</p>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Total Vendors --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Vendors</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($data['total_vendors'] ?? 0) }}</p>
                </div>
            </div>
            {{-- Active Vendors --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Active Vendors</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($data['active_vendors'] ?? $data['approved_vendors'] ?? 0) }}</p>
                </div>
            </div>
            {{-- Avg Performance --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Avg Performance</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['avg_performance'] ?? 0 }}<span class="text-base font-normal text-gray-500 dark:text-gray-400">/100</span></p>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_2fr] gap-5">
            {{-- Vendors by Status Doughnut --}}
            @if(isset($data['by_status']) && count($data['by_status']))
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Vendors by Status</h3>
                <div class="relative" style="height:260px;">
                    <canvas id="vendorStatusChart"></canvas>
                </div>
            </div>
            @endif

            {{-- Performance Comparison Grouped Bar --}}
            @if(isset($data['performance_summary']) && count($data['performance_summary']))
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Vendor Performance Comparison</h3>
                <div class="relative" style="height:260px;">
                    <canvas id="vendorPerformanceChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        {{-- Performance Summary Table --}}
        @if(isset($data['performance_summary']) && count($data['performance_summary']))
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Vendor Performance Summary</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Vendor</th>
                            <th class="px-4 py-3 text-center">Delivery</th>
                            <th class="px-4 py-3 text-center">Quality</th>
                            <th class="px-4 py-3 text-center">Price</th>
                            <th class="px-4 py-3 text-center">Overall</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($data['performance_summary'] as $row)
                        @php $overall = round($row->avg_overall); @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row->vendor_name }}</td>
                            <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ round($row->avg_delivery) }}</td>
                            <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ round($row->avg_quality) }}</td>
                            <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ round($row->avg_price) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $overall >= 80 ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : ($overall >= 60 ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300' : 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300') }}">
                                    {{ $overall }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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

        var statusColorMap = {
            'pending':     '#f59e0b',
            'approved':    '#10b981',
            'suspended':   '#f97316',
            'blacklisted': '#ef4444'
        };

        @if(isset($data['by_status']) && count($data['by_status']))
        (function() {
            var statusData = @json($data['by_status']);
            var labels = statusData.map(function(s) { return s.status.charAt(0).toUpperCase() + s.status.slice(1); });
            var counts = statusData.map(function(s) { return s.count; });
            var colors = statusData.map(function(s) { return statusColorMap[s.status] || '#8b5cf6'; });

            new Chart(document.getElementById('vendorStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: counts,
                        backgroundColor: colors,
                        borderWidth: 0,
                        cutout: '72%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12, font: { size: 11 }, color: textColor } },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    var total = ctx.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                    var pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : '0.0';
                                    return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        })();
        @endif

        @if(isset($data['performance_summary']) && count($data['performance_summary']))
        (function() {
            var perfData = @json(collect($data['performance_summary'])->take(8));
            var labels = perfData.map(function(v) { return v.vendor_name; });

            new Chart(document.getElementById('vendorPerformanceChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Delivery',  data: perfData.map(function(v) { return Math.round(v.avg_delivery); }),  backgroundColor: '#6366f1', borderRadius: 3 },
                        { label: 'Quality',   data: perfData.map(function(v) { return Math.round(v.avg_quality); }),   backgroundColor: '#10b981', borderRadius: 3 },
                        { label: 'Price',     data: perfData.map(function(v) { return Math.round(v.avg_price); }),     backgroundColor: '#f59e0b', borderRadius: 3 },
                        { label: 'Overall',   data: perfData.map(function(v) { return Math.round(v.avg_overall); }),   backgroundColor: '#8b5cf6', borderRadius: 3 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 10, padding: 10, font: { size: 11 }, color: textColor } }
                    },
                    scales: {
                        y: { beginAtZero: true, max: 100, ticks: { color: textColor, font: { size: 11 } }, grid: { color: gridColor } },
                        x: { ticks: { color: textColor, font: { size: 10 } }, grid: { display: false } }
                    }
                }
            });
        })();
        @endif
    });
    </script>
    @endpush
</x-app-layout>
