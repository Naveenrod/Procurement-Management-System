<x-app-layout>
    <x-slot name="title">Score Vendor Performance</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('vendors.performance.store', $vendor) }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">{{ $vendor->name }}</h2>
                    <p class="text-sm text-gray-500">Rate this vendor's performance (0–100)</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Period Start *</label>
                        <input type="date" name="period_start" value="{{ old('period_start') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('period_start')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Period End *</label>
                        <input type="date" name="period_end" value="{{ old('period_end') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('period_end')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Score *</label>
                        <input type="number" name="delivery_score" value="{{ old('delivery_score') }}" min="0" max="100" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('delivery_score')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quality Score *</label>
                        <input type="number" name="quality_score" value="{{ old('quality_score') }}" min="0" max="100" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('quality_score')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price Score *</label>
                        <input type="number" name="price_score" value="{{ old('price_score') }}" min="0" max="100" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('price_score')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Responsiveness Score *</label>
                        <input type="number" name="responsiveness_score" value="{{ old('responsiveness_score') }}" min="0" max="100" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('responsiveness_score')" class="mt-1" />
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
