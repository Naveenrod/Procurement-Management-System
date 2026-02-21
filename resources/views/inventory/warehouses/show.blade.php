<x-app-layout>
    <x-slot name="title">{{ $warehouse->name }}</x-slot>
    <div class="py-6 max-w-5xl space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $warehouse->name }}</h2>
                    <p class="text-sm text-gray-500 font-mono">{{ $warehouse->code }}</p>
                    @if($warehouse->address)<p class="text-sm text-gray-500 mt-1">{{ $warehouse->address }}</p>@endif
                </div>
                <a href="{{ route('inventory.warehouses.edit', $warehouse) }}" class="px-3 py-1.5 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Edit</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-5 py-4 border-b flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Locations</h3>
                <form method="POST" action="{{ route('inventory.warehouses.locations.store', $warehouse) }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="zone" placeholder="Zone" class="border rounded-md px-2 py-1.5 text-xs w-16">
                    <input type="text" name="aisle" placeholder="Aisle" class="border rounded-md px-2 py-1.5 text-xs w-16">
                    <input type="text" name="rack" placeholder="Rack" class="border rounded-md px-2 py-1.5 text-xs w-16">
                    <input type="text" name="shelf" placeholder="Shelf" class="border rounded-md px-2 py-1.5 text-xs w-16">
                    <button class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-md">Add</button>
                </form>
            </div>
            @if($warehouse->locations->count())
            <div class="grid grid-cols-4 gap-3 p-4">
                @foreach($warehouse->locations->groupBy('zone') as $zone => $locs)
                <div class="col-span-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Zone {{ $zone }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($locs as $loc)
                        <span class="inline-flex items-center px-2 py-1 bg-gray-100 rounded text-xs text-gray-700">
                            {{ $loc->zone }}-{{ $loc->aisle }}-{{ $loc->rack }}-{{ $loc->shelf }}
                            @if($loc->bin) ({{ $loc->bin }})@endif
                        </span>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="p-5 text-sm text-gray-500">No locations defined yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>
