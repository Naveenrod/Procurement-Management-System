<x-app-layout>
    <x-slot name="title">{{ $route->name }}</x-slot>
    <div class="py-6 max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $route->name }}</h2>
                    <p class="text-sm text-gray-500 font-mono">{{ $route->code }}</p>
                </div>
                <a href="{{ route('fleet.routes.edit', $route) }}" class="px-3 py-1.5 border rounded-md text-sm text-gray-700">Edit</a>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                <div><p class="text-gray-500">Origin</p><p class="font-medium">{{ $route->origin }}</p></div>
                <div><p class="text-gray-500">Destination</p><p class="font-medium">{{ $route->destination }}</p></div>
                <div><p class="text-gray-500">Distance</p><p class="font-medium">{{ $route->distance_km }} km</p></div>
                <div><p class="text-gray-500">Est. Hours</p><p class="font-medium">{{ $route->estimated_hours ?? '—' }} hrs</p></div>
            </div>
        </div>
    </div>
</x-app-layout>
