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

                <div class="mt-2">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-700">Items *</label>
                        <button type="button" id="add-item" class="text-sm text-blue-600 hover:underline">+ Add Item</button>
                    </div>
                    <table class="w-full text-sm border rounded-md overflow-hidden">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                            <tr>
                                <th class="px-3 py-2 text-left">Product</th>
                                <th class="px-3 py-2 text-left w-32">Qty</th>
                                <th class="px-3 py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            <tr class="item-row border-t">
                                <td class="px-3 py-2">
                                    <select name="items[0][product_id]" required class="w-full border rounded px-2 py-1 text-sm">
                                        <option value="">Select product…</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" name="items[0][expected_quantity]" min="1" required class="w-full border rounded px-2 py-1 text-sm" placeholder="1">
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <button type="button" class="remove-item text-red-500 hover:text-red-700 text-xs">✕</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('warehouse.orders.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Order</button>
            </div>
        </form>
    </div>
    <script>
        const products = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name]));
        let rowIndex = 1;

        document.getElementById('add-item').addEventListener('click', () => {
            const tbody = document.getElementById('items-body');
            const options = products.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
            const row = document.createElement('tr');
            row.className = 'item-row border-t';
            row.innerHTML = `
                <td class="px-3 py-2">
                    <select name="items[${rowIndex}][product_id]" required class="w-full border rounded px-2 py-1 text-sm">
                        <option value="">Select product…</option>${options}
                    </select>
                </td>
                <td class="px-3 py-2">
                    <input type="number" name="items[${rowIndex}][expected_quantity]" min="1" required class="w-full border rounded px-2 py-1 text-sm" placeholder="1">
                </td>
                <td class="px-3 py-2 text-center">
                    <button type="button" class="remove-item text-red-500 hover:text-red-700 text-xs">✕</button>
                </td>`;
            tbody.appendChild(row);
            rowIndex++;
        });

        document.getElementById('items-body').addEventListener('click', e => {
            if (e.target.classList.contains('remove-item')) {
                const rows = document.querySelectorAll('.item-row');
                if (rows.length > 1) e.target.closest('tr').remove();
            }
        });
    </script>
</x-app-layout>
