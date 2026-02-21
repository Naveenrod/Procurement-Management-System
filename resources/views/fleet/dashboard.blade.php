<x-app-layout>
    <x-slot name="title">Fleet Dashboard</x-slot>
    <div class="py-6 space-y-6">
        @php $stats = $stats ?? []; @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stats-card title="Available Vehicles" :value="$stats['available_vehicles'] ?? 0" icon="🚛" color="green" />
            <x-stats-card title="Active Trips" :value="$stats['active_trips'] ?? 0" icon="🗺️" color="blue" href="{{ route('fleet.trips.index') }}" />
            <x-stats-card title="Maintenance Due" :value="$stats['maintenance_due'] ?? 0" icon="🔧" color="yellow" href="{{ route('fleet.maintenance.index') }}" />
            <x-stats-card title="Fuel This Month" :value="'$'.number_format($stats['fuel_cost_month'] ?? 0, 0)" icon="⛽" color="orange" />
        </div>

        @if(isset($activeTrips) && $activeTrips->count())
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-5 py-4 border-b flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Active Trips</h3>
                <a href="{{ route('fleet.trips.index') }}" class="text-sm text-blue-600">View all</a>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Trip #</th><th class="px-4 py-3 text-left">Vehicle</th><th class="px-4 py-3 text-left">Driver</th><th class="px-4 py-3 text-left">Route</th><th class="px-4 py-3 text-left">Started</th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($activeTrips as $trip)
                    <tr><td class="px-4 py-3 font-mono text-xs"><a href="{{ route('fleet.trips.show', $trip) }}" class="text-blue-600">{{ $trip->trip_number }}</a></td><td class="px-4 py-3">{{ optional($trip->vehicle)->registration_number }}</td><td class="px-4 py-3">{{ optional($trip->driver)->name }}</td><td class="px-4 py-3 text-gray-500">{{ optional($trip->route)->name ?? '—' }}</td><td class="px-4 py-3 text-gray-500">{{ optional($trip->started_at)->format('H:i') }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>
