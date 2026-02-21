<x-app-layout>
    <x-slot name="title">Edit Vehicle</x-slot>
    <div class="py-6 max-w-3xl">
        <form method="POST" action="{{ route('fleet.vehicles.update', $vehicle) }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Registration # *</label>
                        <input type="text" name="registration_number" value="{{ old('registration_number', $vehicle->registration_number) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Make *</label>
                        <input type="text" name="make" value="{{ old('make', $vehicle->make) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model *</label>
                        <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border rounded-md px-3 py-2 text-sm">
                            @foreach(\App\Enums\VehicleStatus::cases() as $s)
                            <option value="{{ $s->value }}" @selected(old('status', $vehicle->status) === $s->value)>{{ $s->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mileage</label>
                        <input type="number" name="mileage" value="{{ old('mileage', $vehicle->mileage) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Insurance Expiry</label>
                        <input type="date" name="insurance_expiry" value="{{ old('insurance_expiry', optional($vehicle->insurance_expiry)->format('Y-m-d')) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reg. Expiry</label>
                        <input type="date" name="registration_expiry" value="{{ old('registration_expiry', optional($vehicle->registration_expiry)->format('Y-m-d')) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('fleet.vehicles.show', $vehicle) }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
