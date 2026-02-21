<x-app-layout>
    <x-slot name="title">Spend Analysis</x-slot>
    <div class="py-6 space-y-6">
        <form method="GET" class="bg-white rounded-lg shadow-sm border p-4 flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                <input type="date" name="from" value="{{ request('from', now()->startOfYear()->format('Y-m-d')) }}" class="border rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                <input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}" class="border rounded-md px-3 py-2 text-sm">
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md">Apply</button>
        </form>

        <div class="grid grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Spend by Month</h3>
                <canvas id="monthChart" height="120"></canvas>
            </div>
            <div class="bg-white rounded-lg shadow-sm border p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Spend by Category</h3>
                <canvas id="categoryChart" height="120"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Spend by Vendor</h3></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Vendor</th><th class="px-4 py-3 text-right">Total Spend</th><th class="px-4 py-3 text-right">Orders</th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($spendByVendor ?? [] as $row)
                    <tr><td class="px-4 py-3 font-medium">{{ $row->vendor_name }}</td><td class="px-4 py-3 text-right">${{ number_format($row->total, 2) }}</td><td class="px-4 py-3 text-right">{{ $row->count }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        new Chart(document.getElementById('monthChart'), {
            type: 'bar',
            data: { labels: @json(collect($spendByMonth ?? [])->pluck('month')), datasets: [{ label: '$', data: @json(collect($spendByMonth ?? [])->pluck('total')), backgroundColor: 'rgba(59,130,246,0.6)' }] },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
        new Chart(document.getElementById('categoryChart'), {
            type: 'pie',
            data: { labels: @json(collect($spendByCategory ?? [])->pluck('category_name')), datasets: [{ data: @json(collect($spendByCategory ?? [])->pluck('total')), backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'] }] },
            options: { responsive: true }
        });
    });
    </script>
    @endpush
</x-app-layout>
