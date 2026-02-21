<x-app-layout>
    <x-slot name="title">Reports</x-slot>
    <div class="py-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('reports.procurement') }}" class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                <div class="text-3xl mb-3">📦</div>
                <h3 class="font-semibold text-gray-800">Procurement Report</h3>
                <p class="text-sm text-gray-500 mt-1">PO spend, vendor analysis, cycle times</p>
            </a>
            <a href="{{ route('reports.inventory') }}" class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                <div class="text-3xl mb-3">🏭</div>
                <h3 class="font-semibold text-gray-800">Inventory Report</h3>
                <p class="text-sm text-gray-500 mt-1">Stock levels, valuation, movements</p>
            </a>
            <a href="{{ route('reports.vendor') }}" class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                <div class="text-3xl mb-3">🤝</div>
                <h3 class="font-semibold text-gray-800">Vendor Report</h3>
                <p class="text-sm text-gray-500 mt-1">Performance scores, compliance, risk</p>
            </a>
            <a href="{{ route('reports.fleet') }}" class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                <div class="text-3xl mb-3">🚛</div>
                <h3 class="font-semibold text-gray-800">Fleet Report</h3>
                <p class="text-sm text-gray-500 mt-1">Utilization, fuel efficiency, maintenance costs</p>
            </a>
        </div>
    </div>
</x-app-layout>
