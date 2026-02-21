<x-app-layout>
    <x-slot name="title">{{ $driver->name }}</x-slot>
    <div class="py-6 max-w-3xl space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $driver->name }}</h2>
                    <p class="text-sm text-gray-500 font-mono">{{ $driver->employee_id }}</p>
                </div>
                <div class="flex gap-3">
                    <x-status-badge :status="$driver->status" />
                    <a href="{{ route('fleet.drivers.edit', $driver) }}" class="px-3 py-1.5 border rounded-md text-sm text-gray-700">Edit</a>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
                <div><p class="text-gray-500">Phone</p><p class="font-medium">{{ $driver->phone }}</p></div>
                <div><p class="text-gray-500">License #</p><p class="font-medium font-mono">{{ $driver->license_number }}</p></div>
                <div><p class="text-gray-500">License Expiry</p><p class="font-medium">{{ optional($driver->license_expiry)->format('M d, Y') }}</p></div>
            </div>
        </div>
        @if($driver->trips->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Trip History</h3></div>
            <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Trip #</th><th class="px-4 py-3 text-left">Vehicle</th><th class="px-4 py-3 text-left">Status</th><th class="px-4 py-3 text-left">Scheduled</th></tr></thead>
            <tbody class="divide-y">@foreach($driver->trips->take(10) as $trip)<tr><td class="px-4 py-3"><a href="{{ route('fleet.trips.show', $trip) }}" class="text-blue-600">{{ $trip->trip_number }}</a></td><td class="px-4 py-3">{{ optional($trip->vehicle)->registration_number }}</td><td class="px-4 py-3"><x-status-badge :status="$trip->status" /></td><td class="px-4 py-3 text-gray-500">{{ optional($trip->scheduled_at)->format('M d, Y') }}</td></tr>@endforeach</tbody></table>
        </div>
        @endif
    </div>
</x-app-layout>
