<x-app-layout>
    <x-slot name="title">Score Performance — {{ $vendor->name }}</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('vendors.performance.store', $vendor) }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Period Start *</label>
                        <input type="date" name="period_start" value="{{ old('period_start') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Period End *</label>
                        <input type="date" name="period_end" value="{{ old('period_end') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    @foreach(['delivery_score' => 'Delivery Score', 'quality_score' => 'Quality Score', 'price_score' => 'Price Score', 'responsiveness_score' => 'Responsiveness Score'] as $field => $label)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }} (0–100)</label>
                        <input type="number" name="{{ $field }}" value="{{ old($field) }}" min="0" max="100" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    @endforeach
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('vendors.show', $vendor) }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save Score</button>
            </div>
        </form>
    </div>
</x-app-layout>
