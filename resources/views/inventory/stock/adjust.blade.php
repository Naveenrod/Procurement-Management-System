<x-app-layout>
    <x-slot name="title">Adjust Stock</x-slot>
    <div class="py-6 max-w-xl">
        <form method="POST" action="{{ route('inventory.stock.adjust') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                    <select name="product_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }} ({{ $product->sku }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Warehouse *</label>
                    <select name="warehouse_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <option value="">Select Warehouse</option>
                        @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}" @selected(old('warehouse_id') == $wh->id)>{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adjustment Type *</label>
                    <select name="type" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <option value="add">Add Stock</option>
                        <option value="remove">Remove Stock</option>
                        <option value="set">Set to Value</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}" step="0.01" min="0.01" required class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason / Notes *</label>
                    <textarea name="notes" rows="2" required class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('inventory.stock.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Apply Adjustment</button>
            </div>
        </form>
    </div>
</x-app-layout>
