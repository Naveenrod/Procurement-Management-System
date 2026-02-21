<x-app-layout>
    <x-slot name="title">{{ $shipment->tracking_number }}</x-slot>
    <div class="py-6 max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $shipment->tracking_number }}</h2>
                    <p class="text-sm text-gray-500">{{ optional($shipment->purchaseOrder)->po_number }}</p>
                </div>
                <x-status-badge :status="$shipment->status" />
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                <div><p class="text-gray-500">Carrier</p><p class="font-medium">{{ $shipment->carrier ?? '—' }}</p></div>
                <div><p class="text-gray-500">Shipped At</p><p class="font-medium">{{ optional($shipment->shipped_at)->format('M d, Y H:i') ?? '—' }}</p></div>
                <div><p class="text-gray-500">Estimated Arrival</p><p class="font-medium">{{ optional($shipment->estimated_arrival)->format('M d, Y') ?? '—' }}</p></div>
                <div><p class="text-gray-500">Delivered At</p><p class="font-medium">{{ optional($shipment->delivered_at)->format('M d, Y') ?? '—' }}</p></div>
            </div>
            @if($shipment->notes)<p class="mt-3 text-sm text-gray-600">{{ $shipment->notes }}</p>@endif
        </div>
    </div>
</x-app-layout>
