<x-app-layout>
    <x-slot name="title">New Trip</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('fleet.trips.store') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle *</label>
                        <select name="vehicle_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $v)
                            <option value="{{ $v->id }}">{{ $v->registration_number }} — {{ $v->make }} {{ $v->model }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Driver *</label>
                        <select name="driver_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select Driver</option>
                            @foreach($drivers as $d)
                            <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                        <select name="route_id" class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">No fixed route</option>
                            @foreach($routes as $r)
                            <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->origin }} → {{ $r->destination }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled At *</label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('fleet.trips.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Trip</button>
            </div>
        </form>
    </div>
</x-app-layout>
