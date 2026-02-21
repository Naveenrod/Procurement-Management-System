<x-app-layout>
    <x-slot name="title">{{ $cycleCount->count_number }}</x-slot>
    <div class="py-6 max-w-4xl space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $cycleCount->count_number }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ optional($cycleCount->warehouse)->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-status-badge :status="$cycleCount->status" />
                    @if($cycleCount->status === 'pending' || $cycleCount->status === 'in_progress')
                    <a href="{{ route('inventory.cycle-counts.count-form', $cycleCount) }}" class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md">Start Counting</a>
                    @endif
                    @if($cycleCount->status === 'in_progress' || $cycleCount->status === 'counted')
                    <form method="POST" action="{{ route('inventory.cycle-counts.reconcile', $cycleCount) }}">@csrf
                        <button class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md">Reconcile</button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
                <div><p class="text-gray-500">Created By</p><p class="font-medium">{{ optional($cycleCount->creator)->name }}</p></div>
                <div><p class="text-gray-500">Created At</p><p class="font-medium">{{ $cycleCount->created_at->format('M d, Y H:i') }}</p></div>
                <div><p class="text-gray-500">Completed At</p><p class="font-medium">{{ $cycleCount->completed_at ? $cycleCount->completed_at->format('M d, Y H:i') : '—' }}</p></div>
            </div>
        </div>
        @if($cycleCount->items->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Count Items</h3></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-left">Location</th>
                        <th class="px-4 py-3 text-right">System Qty</th>
                        <th class="px-4 py-3 text-right">Counted Qty</th>
                        <th class="px-4 py-3 text-right">Variance</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($cycleCount->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ optional($item->product)->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($item->location)->zone ?? '—' }}{{ optional($item->location)->aisle ? '-'.optional($item->location)->aisle : '' }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($item->system_quantity, 0) }}</td>
                        <td class="px-4 py-3 text-right">{{ $item->counted_quantity !== null ? number_format($item->counted_quantity, 0) : '—' }}</td>
                        <td class="px-4 py-3 text-right {{ ($item->variance ?? 0) < 0 ? 'text-red-600' : (($item->variance ?? 0) > 0 ? 'text-green-600' : '') }}">{{ $item->variance !== null ? number_format($item->variance, 0) : '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>
