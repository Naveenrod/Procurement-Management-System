<x-app-layout>
    <x-slot name="title">{{ $requisition->requisition_number }}</x-slot>
    <div class="py-6 max-w-4xl space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $requisition->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $requisition->requisition_number }} · {{ $requisition->department }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-status-badge :status="$requisition->status" />
                    @if($requisition->status === 'draft')
                    <form method="POST" action="{{ route('requisitions.submit', $requisition) }}">@csrf
                        <button class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Submit for Approval</button>
                    </form>
                    @endif
                    @if($requisition->status === 'pending_approval' && auth()->user()->hasRole(['admin','manager']))
                    <form method="POST" action="{{ route('requisitions.approve', $requisition) }}">@csrf
                        <button class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('requisitions.reject', $requisition) }}" onsubmit="return confirm('Enter rejection reason:')">@csrf
                        <button class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">Reject</button>
                    </form>
                    @endif
                    @if($requisition->status === 'approved')
                    <a href="{{ route('purchase-orders.create', ['requisition_id' => $requisition->id]) }}" class="px-3 py-1.5 bg-purple-600 text-white text-sm rounded-md hover:bg-purple-700">Convert to PO</a>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-6 text-sm">
                <div><p class="text-gray-500">Priority</p><x-status-badge :status="$requisition->priority" class="mt-1" /></div>
                <div><p class="text-gray-500">Required Date</p><p class="font-medium">{{ optional($requisition->required_date)->format('M d, Y') ?? '—' }}</p></div>
                <div><p class="text-gray-500">Requested By</p><p class="font-medium">{{ optional($requisition->requester)->name ?? '—' }}</p></div>
                <div><p class="text-gray-500">Total Amount</p><p class="font-bold text-lg">${{ number_format($requisition->total_amount, 2) }}</p></div>
                @if($requisition->approved_by)<div><p class="text-gray-500">Approved By</p><p class="font-medium">{{ optional($requisition->approver)->name }}</p></div>@endif
                @if($requisition->rejection_reason)<div class="col-span-3"><p class="text-gray-500">Rejection Reason</p><p class="text-red-700">{{ $requisition->rejection_reason }}</p></div>@endif
            </div>
            @if($requisition->description)<p class="mt-4 text-sm text-gray-600">{{ $requisition->description }}</p>@endif
        </div>

        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Line Items</h3></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Product</th>
                        <th class="px-4 py-3 text-right">Qty</th>
                        <th class="px-4 py-3 text-right">Unit Price</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-left">Specifications</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($requisition->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ optional($item->product)->name }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($item->quantity, 2) }}</td>
                        <td class="px-4 py-3 text-right">${{ number_format($item->estimated_unit_price, 2) }}</td>
                        <td class="px-4 py-3 text-right font-medium">${{ number_format($item->total_price, 2) }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $item->specifications ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
