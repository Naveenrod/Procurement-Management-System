<x-app-layout>
    <x-slot name="title">Edit Purchase Order</x-slot>
    <div class="py-6 max-w-4xl">
        <form method="POST" action="{{ route('purchase-orders.update', $order) }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vendor *</label>
                        <select name="vendor_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" @selected(old('vendor_id', $order->vendor_id) == $vendor->id)>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order Date *</label>
                        <input type="date" name="order_date" value="{{ old('order_date', optional($order->order_date)->format('Y-m-d')) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expected Delivery</label>
                        <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date', optional($order->expected_delivery_date)->format('Y-m-d')) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('purchase-orders.show', $order) }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save Changes</button>
            </div>
        </form>
    </div>
</x-app-layout>
