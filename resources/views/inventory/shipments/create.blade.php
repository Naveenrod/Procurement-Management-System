<x-app-layout>
    <x-slot name="title">Track New Shipment</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('inventory.shipments.store') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Order *</label>
                        <select name="purchase_order_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select PO</option>
                            @foreach($purchaseOrders as $po)
                            <option value="{{ $po->id }}">{{ $po->po_number }} — {{ optional($po->vendor)->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Carrier</label>
                        <input type="text" name="carrier" value="{{ old('carrier') }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shipped At</label>
                        <input type="datetime-local" name="shipped_at" value="{{ old('shipped_at') }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Arrival</label>
                        <input type="date" name="estimated_arrival" value="{{ old('estimated_arrival') }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('inventory.shipments.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
