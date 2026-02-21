<x-app-layout>
    <x-slot name="title">Fleet Report</x-slot>
    <div class="py-6 space-y-6">
        <div class="grid grid-cols-4 gap-4">
            <x-stats-card title="Total Vehicles" :value="$data['total_vehicles'] ?? 0" icon="🚛" color="blue" />
            <x-stats-card title="Total Trips" :value="$data['total_trips'] ?? 0" icon="🗺️" color="green" />
            <x-stats-card title="Fuel Cost" :value="'$'.number_format($data['total_fuel_cost'] ?? 0, 0)" icon="⛽" color="orange" />
            <x-stats-card title="Maintenance Cost" :value="'$'.number_format($data['maintenance_cost'] ?? 0, 0)" icon="🔧" color="yellow" />
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
</x-app-layout>
