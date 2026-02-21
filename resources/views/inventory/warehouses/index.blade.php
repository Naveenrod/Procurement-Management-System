<x-app-layout>
    <x-slot name="title">Warehouses</x-slot>
    <div class="py-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('inventory.warehouses.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New Warehouse</a>
        </div>
        @if($warehouses->count())
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($warehouses as $warehouse)
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $warehouse->name }}</h3>
                        <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $warehouse->code }}</p>
                    </div>
                    <span class="text-xs {{ $warehouse->is_active ? 'text-green-600 bg-green-100' : 'text-gray-500 bg-gray-100' }} px-2 py-0.5 rounded-full">{{ $warehouse->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                @if($warehouse->address)<p class="text-sm text-gray-500 mt-2">{{ $warehouse->address }}</p>@endif
                <div class="mt-3 flex gap-3">
                    <a href="{{ route('inventory.warehouses.show', $warehouse) }}" class="text-sm text-blue-600 hover:underline">View</a>
                    <a href="{{ route('inventory.warehouses.edit', $warehouse) }}" class="text-sm text-gray-500 hover:underline">Edit</a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <x-empty-state title="No warehouses found" action-url="{{ route('inventory.warehouses.create') }}" action-label="New Warehouse" />
        @endif
    </div>
</x-app-layout>
