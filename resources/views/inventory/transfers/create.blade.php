<x-app-layout>
    <x-slot name="title">New Transfer</x-slot>
    <div class="py-6 max-w-3xl">
        <form method="POST" action="{{ route('inventory.transfers.store') }}" x-data="{ items: [{ product_id: '', quantity_requested: 1 }] }">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Warehouse *</label>
                        <select name="from_warehouse_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select</option>
                            @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Warehouse *</label>
                        <select name="to_warehouse_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select</option>
                            @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border p-6 mt-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">Items</h3>
                    <button type="button" @click="items.push({ product_id: '', quantity_requested: 1 })" class="text-sm text-blue-600">+ Add Item</button>
                </div>
                <template x-for="(item, index) in items" :key="index">
                    <div class="grid grid-cols-8 gap-2 mb-3">
                        <div class="col-span-5">
                            <select :name="'items['+index+'][product_id]'" class="w-full border rounded-md px-2 py-2 text-sm">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <input type="number" :name="'items['+index+'][quantity_requested]'" x-model="item.quantity_requested" step="0.01" min="0.01" placeholder="Qty" class="w-full border rounded-md px-2 py-2 text-sm">
                        </div>
                        <div class="col-span-1 flex items-center">
                            <button type="button" @click="items.splice(index,1)" x-show="items.length > 1" class="text-red-400 hover:text-red-600">✕</button>
                        </div>
                    </div>
                </template>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('inventory.transfers.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Transfer</button>
            </div>
        </form>
    </div>
</x-app-layout>
