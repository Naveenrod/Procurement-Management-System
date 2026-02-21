<x-app-layout>
    <x-slot name="title">Edit Maintenance Record</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('fleet.maintenance.update', $record) }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Type *</label>
                        <input type="text" name="type" value="{{ old('type', $record->type) }}" placeholder="e.g. Oil Change, Tyre Rotation" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('type')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled Date *</label>
                        <input type="date" name="scheduled_date" value="{{ old('scheduled_date', optional($record->scheduled_date)->format('Y-m-d')) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('scheduled_date')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Completed Date</label>
                        <input type="date" name="completed_date" value="{{ old('completed_date', optional($record->completed_date)->format('Y-m-d')) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('completed_date')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cost</label>
                        <input type="number" name="cost" value="{{ old('cost', $record->cost) }}" step="0.01" class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('cost')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Performed By</label>
                        <input type="text" name="performed_by" value="{{ old('performed_by', $record->performed_by) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('performed_by')" class="mt-1" />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('description', $record->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes', $record->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('fleet.maintenance.show', $record) }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save Changes</button>
            </div>
        </form>
    </div>
</x-app-layout>
