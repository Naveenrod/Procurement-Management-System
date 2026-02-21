<x-app-layout>
    <x-slot name="title">Warehouse Orders</x-slot>
    <div class="py-6">
        <div class="flex justify-between items-center mb-4">
            <form method="GET" class="flex gap-2">
                <select name="type" class="border rounded-md px-3 py-1.5 text-sm">
                    <option value="">All Types</option>
                    <option value="inbound" @selected(request('type') === 'inbound')>Inbound</option>
                    <option value="outbound" @selected(request('type') === 'outbound')>Outbound</option>
                    <option value="internal" @selected(request('type') === 'internal')>Internal</option>
                </select>
                <select name="status" class="border rounded-md px-3 py-1.5 text-sm">
                    <option value="">All Statuses</option>
                    @foreach(\App\Enums\WarehouseOrderStatus::cases() as $s)
                    <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
                <button class="px-3 py-1.5 bg-gray-100 rounded-md text-sm">Filter</button>
            </form>
            <a href="{{ route('warehouse.orders.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New Order</a>
        </div>
        @if($orders->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Order #</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Warehouse</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Created</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $order->order_number }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$order->type" /></td>
                        <td class="px-4 py-3">{{ optional($order->warehouse)->name }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$order->status" /></td>
                        <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3"><a href="{{ route('warehouse.orders.show', $order) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $orders->links() }}</div>
        @else
        <x-empty-state title="No warehouse orders" action-url="{{ route('warehouse.orders.create') }}" action-label="New Order" />
        @endif
    </div>
</x-app-layout>
