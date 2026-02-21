<x-app-layout>
    <x-slot name="title">{{ $transfer->transfer_number }}</x-slot>
    <div class="py-6 max-w-4xl space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $transfer->transfer_number }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ optional($transfer->fromWarehouse)->name }} → {{ optional($transfer->toWarehouse)->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-status-badge :status="$transfer->status" />
                    @if($transfer->status === 'pending')
                    <form method="POST" action="{{ route('inventory.transfers.approve', $transfer) }}">@csrf
                        <button class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md">Approve</button>
                    </form>
                    @endif
                    @if($transfer->status === 'approved')
                    <form method="POST" action="{{ route('inventory.transfers.ship', $transfer) }}">@csrf
                        <button class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md">Mark Shipped</button>
                    </form>
                    @endif
                    @if($transfer->status === 'shipped')
                    <form method="POST" action="{{ route('inventory.transfers.receive', $transfer) }}">@csrf
                        <button class="px-3 py-1.5 bg-purple-600 text-white text-sm rounded-md">Receive</button>
                    </form>
                    @endif
                </div>
            </div>
            @if($transfer->notes)<p class="mt-3 text-sm text-gray-600">{{ $transfer->notes }}</p>@endif
        </div>
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Items</h3></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Product</th><th class="px-4 py-3 text-right">Requested</th><th class="px-4 py-3 text-right">Shipped</th><th class="px-4 py-3 text-right">Received</th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($transfer->items as $item)
                    <tr><td class="px-4 py-3">{{ optional($item->product)->name }}</td><td class="px-4 py-3 text-right">{{ $item->quantity_requested }}</td><td class="px-4 py-3 text-right">{{ $item->quantity_shipped }}</td><td class="px-4 py-3 text-right">{{ $item->quantity_received }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
