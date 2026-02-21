<x-app-layout>
    <x-slot name="title">Log Fuel</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('fleet.fuel-logs.store') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Vehicle *</label><select name="vehicle_id" required class="w-full border rounded-md px-3 py-2 text-sm"><option value="">Select Vehicle</option>@foreach($vehicles as $v)<option value="{{ $v->id }}">{{ $v->registration_number }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Filled At *</label><input type="datetime-local" name="filled_at" value="{{ old('filled_at', now()->format('Y-m-d\TH:i')) }}" required class="w-full border rounded-md px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Liters *</label><input type="number" name="liters" value="{{ old('liters') }}" step="0.01" required class="w-full border rounded-md px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Cost per Liter *</label><input type="number" name="cost_per_liter" value="{{ old('cost_per_liter') }}" step="0.001" required class="w-full border rounded-md px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Odometer (km) *</label><input type="number" name="odometer_reading" value="{{ old('odometer_reading') }}" step="0.1" required class="w-full border rounded-md px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Station Name</label><input type="text" name="station_name" value="{{ old('station_name') }}" class="w-full border rounded-md px-3 py-2 text-sm"></div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('fleet.fuel-logs.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
