<x-app-layout>
    <x-slot name="title">Add Vehicle</x-slot>
    <div class="py-6 max-w-3xl">
        <form method="POST" action="{{ route('fleet.vehicles.store') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Registration # *</label>
                        <input type="text" name="registration_number" value="{{ old('registration_number') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Make *</label>
                        <input type="text" name="make" value="{{ old('make') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model *</label>
                        <input type="text" name="model" value="{{ old('model') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Year *</label>
                        <input type="number" name="year" value="{{ old('year', date('Y')) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" class="w-full border rounded-md px-3 py-2 text-sm">
                            @foreach(['truck','van','car','motorcycle','forklift'] as $t)
                            <option value="{{ $t }}" @selected(old('type') === $t)>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fuel Type</label>
                        <select name="fuel_type" class="w-full border rounded-md px-3 py-2 text-sm">
                            @foreach(['diesel','petrol','electric','hybrid'] as $f)
                            <option value="{{ $f }}" @selected(old('fuel_type', 'diesel') === $f)>{{ ucfirst($f) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Insurance Expiry</label>
                        <input type="date" name="insurance_expiry" value="{{ old('insurance_expiry') }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reg. Expiry</label>
                        <input type="date" name="registration_expiry" value="{{ old('registration_expiry') }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('fleet.vehicles.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Add Vehicle</button>
            </div>
        </form>
    </div>
</x-app-layout>
