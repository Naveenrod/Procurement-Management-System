<x-app-layout>
    <x-slot name="title">Procurement Report</x-slot>
    <div class="py-6 space-y-6">
        <form method="GET" class="bg-white rounded-lg shadow-sm border p-4 flex gap-4 items-end">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">From</label><input type="date" name="from" value="{{ request('from', now()->startOfYear()->format('Y-m-d')) }}" class="border rounded-md px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">To</label><input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}" class="border rounded-md px-3 py-2 text-sm"></div>
            <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md">Apply</button>
        </form>

        <div class="grid grid-cols-3 gap-4">
            <x-stats-card title="Total POs" :value="$data['total_pos'] ?? 0" icon="📦" color="blue" />
            <x-stats-card title="Total Spend" :value="'$'.number_format($data['total_spend'] ?? 0, 0)" icon="💰" color="green" />
            <x-stats-card title="Avg PO Value" :value="'$'.number_format(($data['total_pos'] ?? 0) > 0 ? ($data['total_spend'] ?? 0) / $data['total_pos'] : 0, 0)" icon="📊" color="purple" />
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @if(isset($spendByMonth) && count($spendByMonth))
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Monthly Spend Trend</h3>
                <div style="position: relative; height: 280px;">
                    <canvas id="procMonthlySpendChart"></canvas>
                </div>
            </div>
            @endif

            @if(isset($spendByCategory) && count($spendByCategory))
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Spend by Category</h3>
                <div style="position: relative; height: 280px;">
                    <canvas id="procCategoryChart"></canvas>
                </div>
            </div>
            @endif

            @if(isset($spendByVendor) && count($spendByVendor))
            <div class="bg-white rounded-lg shadow-sm border p-5 lg:col-span-2">
                <h3 class="font-semibold text-gray-800 mb-4">Top Vendors by Spend</h3>
                <div style="position: relative; height: 300px;">
                    <canvas id="procVendorChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        @if(isset($spendByVendor) && count($spendByVendor))
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Spend by Vendor</h3></div>
            <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Vendor</th><th class="px-4 py-3 text-right">Total</th><th class="px-4 py-3 text-right">PO Count</th></tr></thead>
            <tbody class="divide-y">@foreach($spendByVendor as $row)<tr><td class="px-4 py-3">{{ $row->name }}</td><td class="px-4 py-3 text-right">${{ number_format($row->total, 2) }}</td><td class="px-4 py-3 text-right">{{ $row->po_count }}</td></tr>@endforeach</tbody>
            </table>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var chartColors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'];

        @if(isset($spendByMonth) && count($spendByMonth))
        new Chart(document.getElementById('procMonthlySpendChart'), {
            type: 'bar',
            data: {
                labels: @json(collect($spendByMonth)->pluck('month')),
                datasets: [{
                    label: 'Monthly Spend ($)',
                    data: @json(collect($spendByMonth)->pluck('total')),
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
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
        @endif

        @if(isset($spendByCategory) && count($spendByCategory))
        new Chart(document.getElementById('procCategoryChart'), {
            type: 'doughnut',
            data: {
                labels: @json(collect($spendByCategory)->pluck('name')),
                datasets: [{
                    data: @json(collect($spendByCategory)->pluck('total')),
                    backgroundColor: chartColors.slice(0, {{ count($spendByCategory) }}),
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
                labels: @json(collect($spendByVendor)->pluck('name')),
                datasets: [{
                    label: 'Total Spend ($)',
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
                    x: { beginAtZero: true, ticks: { callback: function(v) { return '$' + v.toLocaleString(); } }, grid: { color: 'rgba(0,0,0,0.05)' } },
                    y: { grid: { display: false } }
                }
            }
        });
        @endif
    });
    </script>
    @endpush
</x-app-layout>
