<x-app-layout>
    <x-slot name="title">Edit Cycle Count</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('inventory.cycle-counts.update', $cycleCount) }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" required class="w-full border rounded-md px-3 py-2 text-sm">
                        @foreach(['pending', 'in_progress', 'completed', 'cancelled'] as $s)
                        <option value="{{ $s }}" @selected(old('status', $cycleCount->status) === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('inventory.cycle-counts.show', $cycleCount) }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save Changes</button>
            </div>
        </form>
    </div>
</x-app-layout>
