<x-app-layout>
    <x-slot name="title">Schedule Maintenance</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('fleet.maintenance.store') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle *</label>
                        <select name="vehicle_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" @selected(request('vehicle_id') == $v->id)>{{ $v->registration_number }} — {{ $v->make }} {{ $v->model }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Type *</label>
                        <input type="text" name="type" value="{{ old('type') }}" placeholder="e.g. Oil Change, Tyre Rotation" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled Date *</label>
                        <input type="date" name="scheduled_date" value="{{ old('scheduled_date') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Cost</label>
                        <input type="number" name="cost" value="{{ old('cost') }}" step="0.01" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('fleet.maintenance.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Schedule</button>
            </div>
        </form>
    </div>
</x-app-layout>
