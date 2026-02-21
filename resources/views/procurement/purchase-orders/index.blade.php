<x-app-layout>
    <x-slot name="title">Purchase Orders</x-slot>
    <div class="py-6">
        <div class="flex justify-between items-center mb-4">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search PO#, vendor..." class="border rounded-md px-3 py-1.5 text-sm">
                <select name="status" class="border rounded-md px-3 py-1.5 text-sm">
                    <option value="">All Statuses</option>
                    @foreach(\App\Enums\PurchaseOrderStatus::cases() as $s)
                    <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
                <button class="px-3 py-1.5 bg-gray-100 rounded-md text-sm">Filter</button>
            </form>
            @can('manage-procurement')
            <a href="{{ route('procurement.purchase-orders.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New PO</a>
            @endcan
        </div>

        @if($orders->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">PO Number</th>
                        <th class="px-4 py-3 text-left">Vendor</th>
                        <th class="px-4 py-3 text-left">Order Date</th>
                        <th class="px-4 py-3 text-left">Expected Delivery</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs font-semibold">{{ $order->po_number }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ optional($order->vendor)->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($order->order_date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $order->expected_delivery_date ? $order->expected_delivery_date->format('M d, Y') : '—' }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$order->status" /></td>
                        <td class="px-4 py-3 text-right font-medium">${{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('procurement.purchase-orders.show', $order) }}" class="text-blue-600 hover:underline text-xs">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $orders->withQueryString()->links() }}</div>
        @else
        <x-empty-state title="No purchase orders found" action-url="{{ route('procurement.purchase-orders.create') }}" action-label="New Purchase Order" />
        @endif
    </div>
</x-app-layout>
