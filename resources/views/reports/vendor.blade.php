<x-app-layout>
    <x-slot name="title">Vendor Report</x-slot>
    <div class="py-6 space-y-6">
        <div class="grid grid-cols-3 gap-4">
            <x-stats-card title="Total Vendors" :value="$data['total_vendors'] ?? 0" icon="🏢" color="blue" />
            <x-stats-card title="Active Vendors" :value="$data['active_vendors'] ?? 0" icon="✓" color="green" />
            <x-stats-card title="Avg Performance" :value="($data['avg_performance'] ?? 0).'/100'" icon="⭐" color="yellow" />
        </div>
        @if(isset($data['performance_summary']) && count($data['performance_summary']))
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Vendor Performance Summary</h3></div>
            <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Vendor</th><th class="px-4 py-3 text-center">Delivery</th><th class="px-4 py-3 text-center">Quality</th><th class="px-4 py-3 text-center">Price</th><th class="px-4 py-3 text-center">Overall</th></tr></thead>
            <tbody class="divide-y">@foreach($data['performance_summary'] as $row)<tr><td class="px-4 py-3 font-medium">{{ $row->vendor_name }}</td><td class="px-4 py-3 text-center">{{ round($row->avg_delivery) }}</td><td class="px-4 py-3 text-center">{{ round($row->avg_quality) }}</td><td class="px-4 py-3 text-center">{{ round($row->avg_price) }}</td><td class="px-4 py-3 text-center font-bold">{{ round($row->avg_overall) }}</td></tr>@endforeach</tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>
