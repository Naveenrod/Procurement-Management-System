<x-app-layout>
    <x-slot name="title">New Cycle Count</x-slot>
    <div class="py-6 max-w-4xl">
        <form method="POST" action="{{ route('inventory.cycle-counts.store') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Warehouse *</label>
                    <select name="warehouse_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <option value="">Select Warehouse</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>{{ $warehouse->name }} ({{ $warehouse->code }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('warehouse_id')" class="mt-1" />
                </div>
                <p class="text-sm text-gray-500">Items will be automatically generated from the warehouse inventory.</p>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('inventory.cycle-counts.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Cycle Count</button>
            </div>
        </form>
    </div>
</x-app-layout>
