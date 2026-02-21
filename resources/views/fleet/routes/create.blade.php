<x-app-layout>
    <x-slot name="title">New Route</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('fleet.routes.store') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Name *</label><input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded-md px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Origin *</label><input type="text" name="origin" value="{{ old('origin') }}" required class="w-full border rounded-md px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Destination *</label><input type="text" name="destination" value="{{ old('destination') }}" required class="w-full border rounded-md px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Distance (km)</label><input type="number" name="distance_km" value="{{ old('distance_km') }}" step="0.1" class="w-full border rounded-md px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Estimated Hours</label><input type="number" name="estimated_hours" value="{{ old('estimated_hours') }}" step="0.5" class="w-full border rounded-md px-3 py-2 text-sm"></div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('fleet.routes.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Route</button>
            </div>
        </form>
    </div>
</x-app-layout>
