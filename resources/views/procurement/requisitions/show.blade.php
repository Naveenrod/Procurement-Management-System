<x-app-layout>
    <x-slot name="title">{{ $requisition->requisition_number }}</x-slot>
    <div class="py-6 max-w-4xl space-y-4" x-data="{ rejectOpen: false }">
<!-- 
        @if(session('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">{{ session('error') }}</div>
        @endif -->

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $requisition->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $requisition->requisition_number }} · {{ $requisition->department }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-status-badge :status="$requisition->status" />

                    @if($requisition->status?->value === 'draft')
                    <form method="POST" action="{{ route('procurement.requisitions.submit', $requisition) }}">@csrf
                        <button class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Submit for Approval</button>
                    </form>
                    @endif

                    @if($requisition->status?->value === 'pending_approval' && auth()->user()->hasRole(['admin','manager']))
                    <form method="POST" action="{{ route('procurement.requisitions.approve', $requisition) }}">@csrf
                        <button class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">Approve</button>
                    </form>
                    <button @click="rejectOpen = true"
                            class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                        Reject
                    </button>
                    @endif

                    @if($requisition->status?->value === 'approved')
                    <a href="{{ route('procurement.purchase-orders.create', ['requisition_id' => $requisition->id]) }}"
                       class="px-3 py-1.5 bg-purple-600 text-white text-sm rounded-md hover:bg-purple-700">Convert to PO</a>
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

        {{-- Reject modal --}}
        <div x-show="rejectOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
             @keydown.escape.window="rejectOpen = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6" @click.stop>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Reject Requisition</h3>
                <form method="POST" action="{{ route('procurement.requisitions.reject', $requisition) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason for rejection <span class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" rows="4" required
                                  placeholder="Explain why this requisition is being rejected…"
                                  class="w-full border rounded-md px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">{{ old('rejection_reason') }}</textarea>
                        <x-input-error :messages="$errors->get('rejection_reason')" class="mt-1" />
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="rejectOpen = false"
                                class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">Confirm Rejection</button>
                    </div>
                </form>
            </div>
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
