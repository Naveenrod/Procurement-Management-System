<x-app-layout>
    <x-slot name="title">Vendor Report</x-slot>
    <div class="py-6 space-y-6">
        <div class="grid grid-cols-3 gap-4">
            <x-stats-card title="Total Vendors" :value="$data['total_vendors'] ?? 0" icon="🏢" color="blue" />
            <x-stats-card title="Active Vendors" :value="$data['active_vendors'] ?? $data['approved_vendors'] ?? 0" icon="✓" color="green" />
            <x-stats-card title="Avg Performance" :value="($data['avg_performance'] ?? 0).'/100'" icon="⭐" color="yellow" />
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @if(isset($data['by_status']) && count($data['by_status']))
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Vendors by Status</h3>
                <div style="position: relative; height: 280px;">
                    <canvas id="vendorStatusChart"></canvas>
                </div>
            </div>
            @endif

            @if(isset($data['performance_summary']) && count($data['performance_summary']))
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Vendor Performance Comparison</h3>
                <div style="position: relative; height: 280px;">
                    <canvas id="vendorPerformanceChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        @if(isset($data['performance_summary']) && count($data['performance_summary']))
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Vendor Performance Summary</h3></div>
            <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Vendor</th><th class="px-4 py-3 text-center">Delivery</th><th class="px-4 py-3 text-center">Quality</th><th class="px-4 py-3 text-center">Price</th><th class="px-4 py-3 text-center">Overall</th></tr></thead>
            <tbody class="divide-y">@foreach($data['performance_summary'] as $row)<tr><td class="px-4 py-3 font-medium">{{ $row->vendor_name }}</td><td class="px-4 py-3 text-center">{{ round($row->avg_delivery) }}</td><td class="px-4 py-3 text-center">{{ round($row->avg_quality) }}</td><td class="px-4 py-3 text-center">{{ round($row->avg_price) }}</td><td class="px-4 py-3 text-center font-bold">{{ round($row->avg_overall) }}</td></tr>@endforeach</tbody>
            </table>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var chartColors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'];
        var statusColorMap = {
            'pending': '#f59e0b',
            'approved': '#10b981',
            'suspended': '#f97316',
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
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    var total = ctx.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                    var pct = ((ctx.parsed / total) * 100).toFixed(1);
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
                        {
                            label: 'Delivery',
                            data: perfData.map(function(v) { return Math.round(v.avg_delivery); }),
                            backgroundColor: '#3b82f6',
                            borderRadius: 2
                        },
                        {
                            label: 'Quality',
                            data: perfData.map(function(v) { return Math.round(v.avg_quality); }),
                            backgroundColor: '#10b981',
                            borderRadius: 2
                        },
                        {
                            label: 'Price',
                            data: perfData.map(function(v) { return Math.round(v.avg_price); }),
                            backgroundColor: '#f59e0b',
                            borderRadius: 2
                        },
                        {
                            label: 'Overall',
                            data: perfData.map(function(v) { return Math.round(v.avg_overall); }),
                            backgroundColor: '#8b5cf6',
                            borderRadius: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 12, padding: 8, font: { size: 10 } } }
                    },
                    scales: {
                        y: { beginAtZero: true, max: 100, grid: { color: 'rgba(0,0,0,0.05)' } },
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                    }
                }
            });
        })();
        @endif
    });
    </script>
    @endpush
</x-app-layout>
