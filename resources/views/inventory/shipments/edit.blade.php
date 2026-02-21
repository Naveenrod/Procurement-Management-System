<x-app-layout>
    <x-slot name="title">Edit Shipment</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('inventory.shipments.update', $shipment) }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Carrier</label>
                        <input type="text" name="carrier" value="{{ old('carrier', $shipment->carrier) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('carrier')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" required class="w-full border rounded-md px-3 py-2 text-sm">
                            @foreach(['pending', 'shipped', 'in_transit', 'delivered', 'cancelled'] as $s)
                            <option value="{{ $s }}" @selected(old('status', $shipment->status) === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shipped At</label>
                        <input type="datetime-local" name="shipped_at" value="{{ old('shipped_at', optional($shipment->shipped_at)->format('Y-m-d\TH:i')) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('shipped_at')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Arrival</label>
                        <input type="date" name="estimated_arrival" value="{{ old('estimated_arrival', optional($shipment->estimated_arrival)->format('Y-m-d')) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('estimated_arrival')" class="mt-1" />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes', $shipment->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('inventory.shipments.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save Changes</button>
            </div>
        </form>
    </div>
</x-app-layout>
