<x-app-layout>
    <x-slot name="title">New Contract</x-slot>
    <div class="py-6 max-w-3xl">
        <form method="POST" action="{{ route('contracts.store') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vendor *</label>
                        <select name="vendor_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select Vendor</option>
                            @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" @selected(old('vendor_id') == $vendor->id)>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contract Value</label>
                        <input type="number" name="value" value="{{ old('value') }}" step="0.01" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
                        <textarea name="terms" rows="3" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('terms') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('contracts.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Contract</button>
            </div>
        </form>
    </div>
</x-app-layout>
