<x-app-layout>
    <x-slot name="title">Shipments</x-slot>
    <div class="py-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('inventory.shipments.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ Track Shipment</a>
        </div>
        @if($shipments->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Tracking #</th>
                        <th class="px-4 py-3 text-left">PO #</th>
                        <th class="px-4 py-3 text-left">Carrier</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">ETA</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($shipments as $shipment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $shipment->tracking_number }}</td>
                        <td class="px-4 py-3"><a href="{{ route('purchase-orders.show', $shipment->purchaseOrder) }}" class="text-blue-600 hover:underline text-xs">{{ optional($shipment->purchaseOrder)->po_number }}</a></td>
                        <td class="px-4 py-3 text-gray-500">{{ $shipment->carrier ?? '—' }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$shipment->status" /></td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($shipment->estimated_arrival)->format('M d, Y') ?? '—' }}</td>
                        <td class="px-4 py-3"><a href="{{ route('inventory.shipments.show', $shipment) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $shipments->links() }}</div>
        @else
        <x-empty-state title="No shipments" action-url="{{ route('inventory.shipments.create') }}" action-label="Track Shipment" />
        @endif
    </div>
</x-app-layout>
