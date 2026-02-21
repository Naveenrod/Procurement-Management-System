<x-app-layout>
    <x-slot name="title">Trips</x-slot>
    <div class="py-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('fleet.trips.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New Trip</a>
        </div>
        @if($trips->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Trip #</th><th class="px-4 py-3 text-left">Vehicle</th><th class="px-4 py-3 text-left">Driver</th><th class="px-4 py-3 text-left">Route</th><th class="px-4 py-3 text-left">Status</th><th class="px-4 py-3 text-left">Scheduled</th><th class="px-4 py-3"></th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($trips as $trip)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $trip->trip_number }}</td>
                        <td class="px-4 py-3">{{ optional($trip->vehicle)->registration_number }}</td>
                        <td class="px-4 py-3">{{ optional($trip->driver)->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($trip->route)->name ?? '—' }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$trip->status" /></td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($trip->scheduled_at)->format('M d, H:i') }}</td>
                        <td class="px-4 py-3"><a href="{{ route('fleet.trips.show', $trip) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $trips->links() }}</div>
        @else
        <x-empty-state title="No trips" action-url="{{ route('fleet.trips.create') }}" action-label="New Trip" />
        @endif
    </div>
</x-app-layout>
