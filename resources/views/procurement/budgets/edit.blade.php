<x-app-layout>
    <x-slot name="title">Edit Budget</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('procurement.budgets.update', $budget) }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                        <input type="text" name="department" value="{{ old('department', $budget->department) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fiscal Year *</label>
                        <input type="number" name="fiscal_year" value="{{ old('fiscal_year', $budget->fiscal_year) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Allocated Amount *</label>
                        <input type="number" name="allocated_amount" value="{{ old('allocated_amount', $budget->allocated_amount) }}" step="0.01" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('procurement.budgets.show', $budget) }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
