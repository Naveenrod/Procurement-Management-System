<x-app-layout>
    <x-slot name="title">Cycle Counts</x-slot>
    <div class="py-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('inventory.cycle-counts.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New Cycle Count</a>
        </div>
        @if($cycleCounts->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Count #</th>
                        <th class="px-4 py-3 text-left">Warehouse</th>
                        <th class="px-4 py-3 text-left">Created By</th>
                        <th class="px-4 py-3 text-left">Completed At</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($cycleCounts as $cycleCount)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs font-semibold">{{ $cycleCount->count_number }}</td>
                        <td class="px-4 py-3">{{ optional($cycleCount->warehouse)->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($cycleCount->creator)->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $cycleCount->completed_at ? $cycleCount->completed_at->format('M d, Y') : '—' }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$cycleCount->status" /></td>
                        <td class="px-4 py-3"><a href="{{ route('inventory.cycle-counts.show', $cycleCount) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $cycleCounts->links() }}</div>
        @else
        <x-empty-state title="No cycle counts found" action-url="{{ route('inventory.cycle-counts.create') }}" action-label="New Cycle Count" />
        @endif
    </div>
</x-app-layout>
