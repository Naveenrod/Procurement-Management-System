<x-app-layout>
    <x-slot name="title">Inventory Transfers</x-slot>
    <div class="py-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('inventory.transfers.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New Transfer</a>
        </div>
        @if($transfers->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Transfer #</th>
                        <th class="px-4 py-3 text-left">From</th>
                        <th class="px-4 py-3 text-left">To</th>
                        <th class="px-4 py-3 text-center">Items</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($transfers as $transfer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $transfer->transfer_number }}</td>
                        <td class="px-4 py-3">{{ optional($transfer->fromWarehouse)->name }}</td>
                        <td class="px-4 py-3">{{ optional($transfer->toWarehouse)->name }}</td>
                        <td class="px-4 py-3 text-center">{{ $transfer->items->count() }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$transfer->status" /></td>
                        <td class="px-4 py-3"><a href="{{ route('inventory.transfers.show', $transfer) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $transfers->links() }}</div>
        @else
        <x-empty-state title="No transfers found" action-url="{{ route('inventory.transfers.create') }}" action-label="New Transfer" />
        @endif
    </div>
</x-app-layout>
