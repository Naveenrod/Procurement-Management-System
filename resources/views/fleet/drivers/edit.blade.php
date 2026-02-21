<x-app-layout>
    <x-slot name="title">Edit Driver</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('fleet.drivers.update', $driver) }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Name *</label><input type="text" name="name" value="{{ old('name', $driver->name) }}" required class="w-full border rounded-md px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label><input type="text" name="phone" value="{{ old('phone', $driver->phone) }}" required class="w-full border rounded-md px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Status</label><select name="status" class="w-full border rounded-md px-3 py-2 text-sm">@foreach(\App\Enums\DriverStatus::cases() as $s)<option value="{{ $s->value }}" @selected(old('status', $driver->status) === $s->value)>{{ $s->label() }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">License Expiry</label><input type="date" name="license_expiry" value="{{ old('license_expiry', optional($driver->license_expiry)->format('Y-m-d')) }}" class="w-full border rounded-md px-3 py-2 text-sm"></div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('fleet.drivers.show', $driver) }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
