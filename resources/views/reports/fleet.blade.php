<x-app-layout>
    <x-slot name="title">Fleet Report</x-slot>
    <div class="py-6 space-y-5 w-full">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Fleet Report</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Vehicle utilization, fuel costs, and trip analytics</p>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
            {{-- Total Vehicles --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Vehicles</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($data['total_vehicles'] ?? 0) }}</p>
                </div>
            </div>
            {{-- Total Trips --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Trips</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($data['total_trips'] ?? 0) }}</p>
                </div>
            </div>
            {{-- Fuel Cost --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Fuel Cost</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($data['total_fuel_cost'] ?? 0, 0) }}</p>
                </div>
            </div>
            {{-- Maintenance Cost --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Maintenance</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($data['maintenance_cost'] ?? 0, 0) }}</p>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 xl:grid-cols-[2fr_1fr] gap-5">
            {{-- Fuel Cost by Month --}}
            @if(isset($data['fuel_by_month']) && count($data['fuel_by_month']))
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Fuel Cost by Month</h3>
                <div class="relative" style="height:260px;">
                    <canvas id="fleetFuelMonthChart"></canvas>
                </div>
            </div>
            @endif

            {{-- Vehicle Utilization Doughnut --}}
            @if(isset($data['by_vehicle']) && count($data['by_vehicle']))
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Trip Distribution</h3>
                <div class="relative" style="height:260px;">
                    <canvas id="fleetVehicleUtilChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        {{-- Vehicle Utilization Table --}}
        @if(isset($data['by_vehicle']) && count($data['by_vehicle']))
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Utilization by Vehicle</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Vehicle</th>
                            <th class="px-4 py-3 text-center">Trips</th>
                            <th class="px-4 py-3 text-right">Fuel Cost</th>
                            <th class="px-4 py-3 text-right">Cost / Trip</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($data['by_vehicle'] as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white font-mono text-xs">{{ $row->registration_number }}</td>
                            <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ number_format($row->trip_count) }}</td>
                            <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">${{ number_format($row->fuel_cost ?? 0, 0) }}</td>
                            <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400">
                                {{ $row->trip_count > 0 ? '$' . number_format(($row->fuel_cost ?? 0) / $row->trip_count, 0) : '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">Total</td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ number_format(collect($data['by_vehicle'])->sum('trip_count')) }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">${{ number_format(collect($data['by_vehicle'])->sum('fuel_cost'), 0) }}</td>
                            <td class="px-4 py-3"></td>
                        </tr>
                    </tfoot>
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
        var chartColors = ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'];

        function fmtMoney(v) { return '$' + (v >= 1000 ? (v/1000).toFixed(1) + 'k' : v.toLocaleString()); }

        @if(isset($data['fuel_by_month']) && count($data['fuel_by_month']))
        new Chart(document.getElementById('fleetFuelMonthChart'), {
            type: 'bar',
            data: {
                labels: @json(collect($data['fuel_by_month'])->pluck('month')),
                datasets: [{
                    label: 'Fuel Cost',
                    data: @json(collect($data['fuel_by_month'])->pluck('total')),
                    backgroundColor: 'rgba(245,158,11,0.75)',
                    borderColor: '#f59e0b',
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

        @if(isset($data['by_vehicle']) && count($data['by_vehicle']))
        (function() {
            var vehicleData = @json(collect($data['by_vehicle'])->take(8));
            var labels = vehicleData.map(function(v) { return v.registration_number; });
            var trips  = vehicleData.map(function(v) { return v.trip_count; });

            new Chart(document.getElementById('fleetVehicleUtilChart'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: trips,
                        backgroundColor: chartColors.slice(0, labels.length),
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
                                    return ctx.label + ': ' + ctx.parsed + ' trips (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        })();
        @endif
    });
    </script>
    @endpush
</x-app-layout>
