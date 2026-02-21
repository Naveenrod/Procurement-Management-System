<x-app-layout>
    <x-slot name="title">Fleet Routes</x-slot>
    <div class="py-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('fleet.routes.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New Route</a>
        </div>
        @if($routes->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Code</th><th class="px-4 py-3 text-left">Name</th><th class="px-4 py-3 text-left">Origin → Destination</th><th class="px-4 py-3 text-right">Distance</th><th class="px-4 py-3 text-right">Est. Hours</th><th class="px-4 py-3"></th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($routes as $route)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $route->code }}</td>
                        <td class="px-4 py-3 font-medium">{{ $route->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $route->origin }} → {{ $route->destination }}</td>
                        <td class="px-4 py-3 text-right">{{ $route->distance_km }} km</td>
                        <td class="px-4 py-3 text-right">{{ $route->estimated_hours ?? '—' }} hrs</td>
                        <td class="px-4 py-3"><a href="{{ route('fleet.routes.show', $route) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $routes->links() }}</div>
        @else
        <x-empty-state title="No routes" action-url="{{ route('fleet.routes.create') }}" action-label="New Route" />
        @endif
    </div>
</x-app-layout>
