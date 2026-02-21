<x-app-layout>
    <x-slot name="title">{{ $vehicle->registration_number }}</x-slot>
    <div class="py-6 max-w-4xl space-y-4" x-data="{ tab: 'details' }">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $vehicle->registration_number }}</h2>
                    <p class="text-sm text-gray-500">{{ $vehicle->make }} {{ $vehicle->model }} {{ $vehicle->year }} · {{ $vehicle->type }}</p>
                </div>
                <div class="flex gap-3">
                    <x-status-badge :status="$vehicle->status" />
                    <a href="{{ route('fleet.vehicles.edit', $vehicle) }}" class="px-3 py-1.5 border rounded-md text-sm text-gray-700">Edit</a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border">
            <div class="border-b flex">
                <button @click="tab='details'" :class="tab==='details'?'border-b-2 border-blue-600 text-blue-600':'text-gray-500'" class="px-5 py-3 text-sm font-medium -mb-px">Details</button>
                <button @click="tab='trips'" :class="tab==='trips'?'border-b-2 border-blue-600 text-blue-600':'text-gray-500'" class="px-5 py-3 text-sm font-medium -mb-px">Trips ({{ $vehicle->trips->count() }})</button>
                <button @click="tab='maintenance'" :class="tab==='maintenance'?'border-b-2 border-blue-600 text-blue-600':'text-gray-500'" class="px-5 py-3 text-sm font-medium -mb-px">Maintenance</button>
                <button @click="tab='fuel'" :class="tab==='fuel'?'border-b-2 border-blue-600 text-blue-600':'text-gray-500'" class="px-5 py-3 text-sm font-medium -mb-px">Fuel Logs</button>
            </div>
            <div x-show="tab==='details'" class="p-6 grid grid-cols-3 gap-4 text-sm">
                <div><p class="text-gray-500">Mileage</p><p class="font-medium">{{ number_format($vehicle->mileage) }} km</p></div>
                <div><p class="text-gray-500">Fuel Type</p><p class="font-medium">{{ $vehicle->fuel_type }}</p></div>
                <div><p class="text-gray-500">Insurance Exp.</p><p class="font-medium">{{ optional($vehicle->insurance_expiry)->format('M d, Y') ?? '—' }}</p></div>
                <div><p class="text-gray-500">Registration Exp.</p><p class="font-medium">{{ optional($vehicle->registration_expiry)->format('M d, Y') ?? '—' }}</p></div>
            </div>
            <div x-show="tab==='trips'" class="p-4">
                @if($vehicle->trips->count())<table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-3 py-2 text-left">Trip #</th><th class="px-3 py-2 text-left">Driver</th><th class="px-3 py-2 text-left">Status</th><th class="px-3 py-2 text-left">Scheduled</th></tr></thead><tbody class="divide-y">@foreach($vehicle->trips as $trip)<tr><td class="px-3 py-2"><a href="{{ route('fleet.trips.show', $trip) }}" class="text-blue-600">{{ $trip->trip_number }}</a></td><td class="px-3 py-2">{{ optional($trip->driver)->name }}</td><td class="px-3 py-2"><x-status-badge :status="$trip->status" /></td><td class="px-3 py-2 text-gray-500">{{ optional($trip->scheduled_at)->format('M d') }}</td></tr>@endforeach</tbody></table>
                @else<p class="text-sm text-gray-500">No trips yet.</p>@endif
            </div>
            <div x-show="tab==='maintenance'" class="p-4">
                <div class="flex justify-end mb-3"><a href="{{ route('fleet.maintenance.create', ['vehicle_id' => $vehicle->id]) }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-md">+ Schedule</a></div>
                @if($vehicle->maintenanceRecords->count())<table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-3 py-2 text-left">Type</th><th class="px-3 py-2 text-left">Scheduled</th><th class="px-3 py-2 text-right">Cost</th></tr></thead><tbody class="divide-y">@foreach($vehicle->maintenanceRecords as $rec)<tr><td class="px-3 py-2">{{ $rec->type }}</td><td class="px-3 py-2 text-gray-500">{{ optional($rec->scheduled_date)->format('M d, Y') }}</td><td class="px-3 py-2 text-right">${{ number_format($rec->cost ?? 0, 2) }}</td></tr>@endforeach</tbody></table>
                @else<p class="text-sm text-gray-500">No maintenance records.</p>@endif
            </div>
            <div x-show="tab==='fuel'" class="p-4">
                @if($vehicle->fuelLogs->count())<table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-3 py-2 text-left">Date</th><th class="px-3 py-2 text-right">Liters</th><th class="px-3 py-2 text-right">Total Cost</th><th class="px-3 py-2 text-right">Odometer</th></tr></thead><tbody class="divide-y">@foreach($vehicle->fuelLogs->take(10) as $log)<tr><td class="px-3 py-2 text-gray-500">{{ optional($log->filled_at)->format('M d, Y') }}</td><td class="px-3 py-2 text-right">{{ $log->liters }}</td><td class="px-3 py-2 text-right">${{ number_format($log->total_cost, 2) }}</td><td class="px-3 py-2 text-right">{{ number_format($log->odometer_reading) }}</td></tr>@endforeach</tbody></table>
                @else<p class="text-sm text-gray-500">No fuel logs.</p>@endif
            </div>
        </div>
    </div>
</x-app-layout>
