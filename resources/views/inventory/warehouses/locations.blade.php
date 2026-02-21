<x-app-layout>
    <x-slot name="title">{{ $warehouse->name }} — Locations</x-slot>
    <div class="py-6 max-w-5xl space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $warehouse->name }}</h2>
                    <p class="text-sm text-gray-500 font-mono">{{ $warehouse->code }}</p>
                </div>
                <a href="{{ route('inventory.warehouses.show', $warehouse) }}" class="px-3 py-1.5 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Back</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-5 py-4 border-b flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Locations</h3>
            </div>

            {{-- Add Location Form --}}
            <div class="px-5 py-4 border-b bg-gray-50">
                <form method="POST" action="{{ route('inventory.warehouses.locations.store', $warehouse) }}" class="flex items-end gap-2 flex-wrap">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Zone</label>
                        <input type="text" name="zone" value="{{ old('zone') }}" placeholder="Zone" class="border rounded-md px-2 py-1.5 text-xs w-20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Aisle</label>
                        <input type="text" name="aisle" value="{{ old('aisle') }}" placeholder="Aisle" class="border rounded-md px-2 py-1.5 text-xs w-20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Rack</label>
                        <input type="text" name="rack" value="{{ old('rack') }}" placeholder="Rack" class="border rounded-md px-2 py-1.5 text-xs w-20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Shelf</label>
                        <input type="text" name="shelf" value="{{ old('shelf') }}" placeholder="Shelf" class="border rounded-md px-2 py-1.5 text-xs w-20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Bin</label>
                        <input type="text" name="bin" value="{{ old('bin') }}" placeholder="Bin" class="border rounded-md px-2 py-1.5 text-xs w-20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Capacity</label>
                        <input type="number" name="capacity" value="{{ old('capacity') }}" placeholder="0" step="1" min="0" class="border rounded-md px-2 py-1.5 text-xs w-20">
                    </div>
                    <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700">Add Location</button>
                </form>
                <x-input-error :messages="$errors->get('zone')" class="mt-1" />
                <x-input-error :messages="$errors->get('aisle')" class="mt-1" />
            </div>

            @if($warehouse->locations->count())
            <div class="overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">Zone</th>
                            <th class="px-4 py-3 text-left">Aisle</th>
                            <th class="px-4 py-3 text-left">Rack</th>
                            <th class="px-4 py-3 text-left">Shelf</th>
                            <th class="px-4 py-3 text-left">Bin</th>
                            <th class="px-4 py-3 text-right">Capacity</th>
                            <th class="px-4 py-3 text-right">Occupied</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($warehouse->locations as $location)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $location->zone ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $location->aisle ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $location->rack ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $location->shelf ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $location->bin ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">{{ $location->capacity ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">{{ $location->occupied ?? 0 }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="p-5 text-sm text-gray-500">No locations defined yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>
