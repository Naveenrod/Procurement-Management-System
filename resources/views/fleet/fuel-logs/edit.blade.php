<x-app-layout>
    <x-slot name="title">Edit Fuel Log</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('fleet.fuel-logs.update', $fuelLog) }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle *</label>
                        <select name="vehicle_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" @selected(old('vehicle_id', $fuelLog->vehicle_id) == $v->id)>{{ $v->registration_number }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('vehicle_id')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trip</label>
                        <select name="trip_id" class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">No trip</option>
                            @foreach($trips as $t)
                            <option value="{{ $t->id }}" @selected(old('trip_id', $fuelLog->trip_id) == $t->id)>{{ $t->trip_number }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('trip_id')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fuel Type *</label>
                        <select name="fuel_type" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select Type</option>
                            <option value="petrol" @selected(old('fuel_type', $fuelLog->fuel_type) === 'petrol')>Petrol</option>
                            <option value="diesel" @selected(old('fuel_type', $fuelLog->fuel_type) === 'diesel')>Diesel</option>
                            <option value="electric" @selected(old('fuel_type', $fuelLog->fuel_type) === 'electric')>Electric</option>
                            <option value="hybrid" @selected(old('fuel_type', $fuelLog->fuel_type) === 'hybrid')>Hybrid</option>
                        </select>
                        <x-input-error :messages="$errors->get('fuel_type')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Filled At *</label>
                        <input type="datetime-local" name="filled_at" value="{{ old('filled_at', optional($fuelLog->filled_at)->format('Y-m-d\TH:i')) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('filled_at')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Liters *</label>
                        <input type="number" name="liters" value="{{ old('liters', $fuelLog->liters) }}" step="0.01" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('liters')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cost per Liter *</label>
                        <input type="number" name="cost_per_liter" value="{{ old('cost_per_liter', $fuelLog->cost_per_liter) }}" step="0.001" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('cost_per_liter')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Odometer (km) *</label>
                        <input type="number" name="odometer_reading" value="{{ old('odometer_reading', $fuelLog->odometer_reading) }}" step="0.1" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('odometer_reading')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Station Name</label>
                        <input type="text" name="station_name" value="{{ old('station_name', $fuelLog->station_name) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('station_name')" class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('fleet.fuel-logs.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save Changes</button>
            </div>
        </form>
    </div>
</x-app-layout>
