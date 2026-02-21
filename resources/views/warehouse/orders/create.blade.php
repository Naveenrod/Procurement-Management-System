<x-app-layout>
    <x-slot name="title">New Warehouse Order</x-slot>
    <div class="py-6 max-w-2xl">
        <form method="POST" action="{{ route('warehouse.orders.store') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Warehouse *</label>
                        <select name="warehouse_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select</option>
                            @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order Type *</label>
                        <select name="type" required class="w-full border rounded-md px-3 py-2 text-sm">
                            @foreach(\App\Enums\WarehouseOrderType::cases() as $t)
                            <option value="{{ $t->value }}">{{ $t->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Linked PO (optional)</label>
                        <select name="purchase_order_id" class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">None</option>
                            @foreach($purchaseOrders ?? [] as $po)
                            <option value="{{ $po->id }}">{{ $po->po_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('warehouse.orders.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Order</button>
            </div>
        </form>
    </div>
</x-app-layout>
