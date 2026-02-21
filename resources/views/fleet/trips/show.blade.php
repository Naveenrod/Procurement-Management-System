<x-app-layout>
    <x-slot name="title">{{ $trip->trip_number }}</x-slot>
    <div class="py-6 max-w-3xl space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $trip->trip_number }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ optional($trip->vehicle)->registration_number }} · {{ optional($trip->driver)->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-status-badge :status="$trip->status" />
                    @if($trip->status === 'scheduled')
                    <form method="POST" action="{{ route('fleet.trips.start', $trip) }}">@csrf
                        <button class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md">Start Trip</button>
                    </form>
                    @endif
                    @if($trip->status === 'in_progress')
                    <form method="POST" action="{{ route('fleet.trips.complete', $trip) }}">@csrf
                        <button class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md">Complete</button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
                @if($trip->route)<div><p class="text-gray-500">Route</p><p class="font-medium">{{ $trip->route->name }}</p><p class="text-xs text-gray-400">{{ $trip->route->origin }} → {{ $trip->route->destination }}</p></div>@endif
                <div><p class="text-gray-500">Scheduled</p><p class="font-medium">{{ optional($trip->scheduled_at)->format('M d, Y H:i') }}</p></div>
                @if($trip->started_at)<div><p class="text-gray-500">Started</p><p class="font-medium">{{ optional($trip->started_at)->format('M d, Y H:i') }}</p></div>@endif
                @if($trip->completed_at)<div><p class="text-gray-500">Completed</p><p class="font-medium">{{ optional($trip->completed_at)->format('M d, Y H:i') }}</p></div>@endif
            </div>
        </div>
        @if($trip->checkpoints->count())
        <div class="bg-white rounded-lg shadow-sm border p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Checkpoints</h3>
            <div class="space-y-3">
                @foreach($trip->checkpoints as $cp)
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $cp->location_name }}</p>
                        @if($cp->arrived_at)<p class="text-xs text-gray-500">Arrived: {{ optional($cp->arrived_at)->format('H:i') }}</p>@endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
