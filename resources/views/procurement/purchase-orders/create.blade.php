<x-app-layout>
    <x-slot name="title">New Purchase Order</x-slot>
    <div class="py-6 max-w-5xl">
        <form method="POST" action="{{ route('purchase-orders.store') }}" x-data="{ items: [{ product_id: '', quantity: 1, unit_price: 0 }] }">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vendor *</label>
                        <select name="vendor_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select Vendor</option>
                            @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" @selected(old('vendor_id') == $vendor->id)>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('vendor_id')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order Date *</label>
                        <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expected Delivery</label>
                        <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date') }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    @if(isset($requisition))
                    <input type="hidden" name="purchase_requisition_id" value="{{ $requisition->id }}">
                    @endif
                    <div class="col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6 mt-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">Line Items</h3>
                    <button type="button" @click="items.push({ product_id: '', quantity: 1, unit_price: 0 })" class="text-sm text-blue-600 hover:underline">+ Add Item</button>
                </div>
                <template x-for="(item, index) in items" :key="index">
                    <div class="grid grid-cols-12 gap-2 mb-3">
                        <div class="col-span-5">
                            <select :name="'items['+index+'][product_id]'" class="w-full border rounded-md px-2 py-2 text-sm">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" step="0.01" min="0.01" placeholder="Qty" class="w-full border rounded-md px-2 py-2 text-sm">
                        </div>
                        <div class="col-span-3">
                            <input type="number" :name="'items['+index+'][unit_price]'" x-model="item.unit_price" step="0.01" placeholder="Unit Price" class="w-full border rounded-md px-2 py-2 text-sm">
                        </div>
                        <div class="col-span-1 flex items-center">
                            <span class="text-sm text-gray-600" x-text="'$'+(item.quantity * item.unit_price).toFixed(2)"></span>
                        </div>
                        <div class="col-span-1 flex items-center">
                            <button type="button" @click="items.splice(index,1)" x-show="items.length > 1" class="text-red-400 hover:text-red-600">✕</button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('purchase-orders.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Purchase Order</button>
            </div>
        </form>
    </div>
</x-app-layout>
