<x-app-layout>
    <x-slot name="title">Warehouse Activity Log</x-slot>
    <div class="py-6">
        <form method="GET" class="flex gap-2 mb-4">
            <select name="warehouse_id" class="border rounded-md px-3 py-1.5 text-sm">
                <option value="">All Warehouses</option>
                @foreach($warehouses ?? [] as $wh)
                <option value="{{ $wh->id }}" @selected(request('warehouse_id') == $wh->id)>{{ $wh->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ request('date') }}" class="border rounded-md px-3 py-1.5 text-sm">
            <button class="px-3 py-1.5 bg-gray-100 rounded-md text-sm">Filter</button>
        </form>
        @if($activities->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Time</th><th class="px-4 py-3 text-left">User</th><th class="px-4 py-3 text-left">Type</th><th class="px-4 py-3 text-left">Product</th><th class="px-4 py-3 text-right">Qty</th><th class="px-4 py-3 text-left">Location</th><th class="px-4 py-3 text-left">Description</th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($activities as $activity)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-xs text-gray-400">{{ $activity->created_at->format('M d H:i') }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ optional($activity->user)->name }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">{{ $activity->type }}</span></td>
                        <td class="px-4 py-3">{{ optional($activity->product)->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">{{ $activity->quantity ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ optional($activity->location)->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ Str::limit($activity->description, 60) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $activities->links() }}</div>
        @else
        <x-empty-state title="No activity records" />
        @endif
    </div>
</x-app-layout>
