<x-app-layout>
    <x-slot name="title">Edit Trip</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('fleet.trips.update', $trip) }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle *</label>
                        <select name="vehicle_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" @selected(old('vehicle_id', $trip->vehicle_id) == $v->id)>{{ $v->registration_number }} — {{ $v->make }} {{ $v->model }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('vehicle_id')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Driver *</label>
                        <select name="driver_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select Driver</option>
                            @foreach($drivers as $d)
                            <option value="{{ $d->id }}" @selected(old('driver_id', $trip->driver_id) == $d->id)>{{ $d->name }} ({{ $d->employee_id }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('driver_id')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                        <select name="route_id" class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">No fixed route</option>
                            @foreach($routes as $r)
                            <option value="{{ $r->id }}" @selected(old('route_id', $trip->route_id) == $r->id)>{{ $r->name }} ({{ $r->origin }} → {{ $r->destination }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('route_id')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled At *</label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', optional($trip->scheduled_at)->format('Y-m-d\TH:i')) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('scheduled_at')" class="mt-1" />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes', $trip->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('fleet.trips.show', $trip) }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save Changes</button>
            </div>
        </form>
    </div>
</x-app-layout>
