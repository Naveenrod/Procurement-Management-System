<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    @php $stats = $stats ?? []; @endphp

    <div class="py-6 space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @if(isset($stats['total_pos']))
            <x-stats-card title="Purchase Orders" :value="$stats['total_pos']" icon="📦" color="blue" href="{{ route('purchase-orders.index') }}" />
            @endif
            @if(isset($stats['pending_approvals']))
            <x-stats-card title="Pending Approvals" :value="$stats['pending_approvals']" icon="⏳" color="yellow" />
            @endif
            @if(isset($stats['low_stock_items']))
            <x-stats-card title="Low Stock Items" :value="$stats['low_stock_items']" icon="⚠️" color="red" href="{{ route('inventory.reorders.index') }}" />
            @endif
            @if(isset($stats['active_vendors']))
            <x-stats-card title="Active Vendors" :value="$stats['active_vendors']" icon="🏢" color="green" href="{{ route('vendors.index') }}" />
            @endif
            @if(isset($stats['my_requisitions']))
            <x-stats-card title="My Requisitions" :value="$stats['my_requisitions']" icon="📋" color="blue" href="{{ route('requisitions.index') }}" />
            @endif
            @if(isset($stats['pending_receipts']))
            <x-stats-card title="Pending Receipts" :value="$stats['pending_receipts']" icon="📬" color="orange" />
            @endif
            @if(isset($stats['orders_to_process']))
            <x-stats-card title="Orders to Process" :value="$stats['orders_to_process']" icon="🏭" color="purple" href="{{ route('warehouse.receiving.index') }}" />
            @endif
            @if(isset($stats['total_spend_month']))
            <x-stats-card title="Spend This Month" :value="'$'.number_format($stats['total_spend_month'], 0)" icon="💰" color="green" />
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @if(isset($recentRequisitions) && $recentRequisitions->count())
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-5 py-4 border-b flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Recent Requisitions</h3>
                    <a href="{{ route('requisitions.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
                </div>
                <div class="divide-y">
                    @foreach($recentRequisitions as $req)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $req->requisition_number }}</p>
                            <p class="text-xs text-gray-500">{{ Str::limit($req->title, 40) }} · {{ $req->department }}</p>
                        </div>
                        <x-status-badge :status="$req->status" />
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(isset($pendingInvoices) && $pendingInvoices->count())
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-5 py-4 border-b flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Pending Invoices</h3>
                    <a href="{{ route('invoices.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
                </div>
                <div class="divide-y">
                    @foreach($pendingInvoices as $inv)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $inv->invoice_number }}</p>
                            <p class="text-xs text-gray-500">{{ optional($inv->vendor)->name ?? '—' }} · ${{ number_format($inv->total_amount, 2) }}</p>
                        </div>
                        <x-status-badge :status="$inv->status" />
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        @if(isset($spendChart) && count($spendChart))
        <div class="bg-white rounded-lg shadow-sm border p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Spend by Month</h3>
            <canvas id="spendChart" height="80"></canvas>
        </div>
        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Chart(document.getElementById('spendChart'), {
                type: 'bar',
                data: {
                    labels: @json(collect($spendChart)->pluck('month')),
                    datasets: [{ label: 'Total Spend ($)', data: @json(collect($spendChart)->pluck('total')), backgroundColor: 'rgba(59,130,246,0.6)' }]
                },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        });
        </script>
        @endpush
        @endif
    </div>
</x-app-layout>
