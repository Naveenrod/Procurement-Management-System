<x-app-layout>
    <x-slot name="title">Procurement Report</x-slot>
    <div class="py-6 space-y-6">
        <form method="GET" class="bg-white rounded-lg shadow-sm border p-4 flex gap-4 items-end">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">From</label><input type="date" name="from" value="{{ request('from', now()->startOfYear()->format('Y-m-d')) }}" class="border rounded-md px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">To</label><input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}" class="border rounded-md px-3 py-2 text-sm"></div>
            <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md">Apply</button>
        </form>

        <div class="grid grid-cols-3 gap-4">
            <x-stats-card title="Total POs" :value="$data['total_pos'] ?? 0" icon="📦" color="blue" />
            <x-stats-card title="Total Spend" :value="'$'.number_format($data['total_spend'] ?? 0, 0)" icon="💰" color="green" />
            <x-stats-card title="Avg PO Value" :value="'$'.number_format($data['avg_po_value'] ?? 0, 0)" icon="📊" color="purple" />
        </div>

        @if(isset($data['spend_by_vendor']) && count($data['spend_by_vendor']))
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Spend by Vendor</h3></div>
            <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Vendor</th><th class="px-4 py-3 text-right">Total</th><th class="px-4 py-3 text-right">PO Count</th></tr></thead>
            <tbody class="divide-y">@foreach($data['spend_by_vendor'] as $row)<tr><td class="px-4 py-3">{{ $row->vendor_name }}</td><td class="px-4 py-3 text-right">${{ number_format($row->total, 2) }}</td><td class="px-4 py-3 text-right">{{ $row->count }}</td></tr>@endforeach</tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>
