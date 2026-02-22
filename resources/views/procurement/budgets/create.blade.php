<x-app-layout>
    <x-slot name="title">New Budget</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('procurement.budgets.store') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                        <select name="department" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select Department</option>
                            @foreach(config('departments') as $dept)
                            <option value="{{ $dept }}" @selected(old('department') === $dept)>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fiscal Year *</label>
                        <input type="number" name="fiscal_year" value="{{ old('fiscal_year', date('Y')) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Allocated Amount *</label>
                        <input type="number" name="allocated_amount" value="{{ old('allocated_amount') }}" step="0.01" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('procurement.budgets.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Budget</button>
            </div>
        </form>
    </div>
</x-app-layout>
