<x-app-layout>
    <x-slot name="title">Fleet Report</x-slot>
    <div class="py-6 space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stats-card title="Total Vehicles" :value="$data['total_vehicles'] ?? 0" icon="🚛" color="blue" />
            <x-stats-card title="Total Trips" :value="$data['total_trips'] ?? 0" icon="🗺️" color="green" />
            <x-stats-card title="Fuel Cost" :value="'$'.number_format($data['total_fuel_cost'] ?? 0, 0)" icon="⛽" color="orange" />
            <x-stats-card title="Maintenance Cost" :value="'$'.number_format($data['maintenance_cost'] ?? 0, 0)" icon="🔧" color="yellow" />
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @if(isset($data['fuel_by_month']) && count($data['fuel_by_month']))
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Fuel Cost by Month</h3>
                <div style="position: relative; height: 280px;">
                    <canvas id="fleetFuelMonthChart"></canvas>
                </div>
            </div>
            @endif

            @if(isset($data['by_vehicle']) && count($data['by_vehicle']))
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Vehicle Utilization (Trips)</h3>
                <div style="position: relative; height: 280px;">
                    <canvas id="fleetVehicleUtilChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        @if(isset($data['by_vehicle']) && count($data['by_vehicle']))
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Utilization by Vehicle</h3></div>
            <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Vehicle</th><th class="px-4 py-3 text-center">Trips</th><th class="px-4 py-3 text-right">Fuel Cost</th></tr></thead>
            <tbody class="divide-y">@foreach($data['by_vehicle'] as $row)<tr><td class="px-4 py-3">{{ $row->registration_number }}</td><td class="px-4 py-3 text-center">{{ $row->trip_count }}</td><td class="px-4 py-3 text-right">${{ number_format($row->fuel_cost ?? 0, 2) }}</td></tr>@endforeach</tbody>
            </table>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var chartColors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'];

        @if(isset($data['fuel_by_month']) && count($data['fuel_by_month']))
        new Chart(document.getElementById('fleetFuelMonthChart'), {
            type: 'bar',
            data: {
                labels: @json(collect($data['fuel_by_month'])->pluck('month')),
                datasets: [{
                    label: 'Fuel Cost ($)',
                    data: @json(collect($data['fuel_by_month'])->pluck('total')),
                    backgroundColor: 'rgba(249, 115, 22, 0.7)',
                    borderColor: '#f97316',
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

        @if(isset($data['by_vehicle']) && count($data['by_vehicle']))
        (function() {
            var vehicleData = @json(collect($data['by_vehicle'])->take(8));
            var labels = vehicleData.map(function(v) { return v.registration_number; });
            var trips = vehicleData.map(function(v) { return v.trip_count; });

            new Chart(document.getElementById('fleetVehicleUtilChart'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: trips,
                        backgroundColor: chartColors.slice(0, labels.length),
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
