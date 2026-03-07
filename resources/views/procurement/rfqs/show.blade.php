<x-app-layout>
    <x-slot name="title">{{ $rfq->rfq_number }}</x-slot>
    <div class="py-6 max-w-5xl space-y-4">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $rfq->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $rfq->rfq_number }} · Closes {{ optional($rfq->closing_date)->format('M d, Y') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('procurement.rfqs.pdf', $rfq) }}"
                       class="px-3 py-1.5 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700">
                        Download PDF
                    </a>
                    <x-status-badge :status="$rfq->status" />
                    @if($rfq->status?->value === 'draft')
                    <form method="POST" action="{{ route('procurement.rfqs.publish', $rfq) }}">@csrf
                        <button class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md">Publish</button>
                    </form>
                    @endif
                    @if($rfq->status?->value === 'published')
                    <form method="POST" action="{{ route('procurement.rfqs.close', $rfq) }}">@csrf
                        <button class="px-3 py-1.5 bg-gray-600 text-white text-sm rounded-md">Close</button>
                    </form>
                    @endif
                </div>
            </div>
            @if($rfq->description)<p class="mt-3 text-sm text-gray-600">{{ $rfq->description }}</p>@endif
        </div>

        @if($rfq->responses->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Vendor Responses</h3></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Vendor</th>
                        <th class="px-4 py-3 text-right">Total Amount</th>
                        <th class="px-4 py-3 text-center">Delivery Days</th>
                        <th class="px-4 py-3 text-left">Payment Terms</th>
                        <th class="px-4 py-3 text-center">Selected</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($rfq->responses as $response)
                    <tr class="{{ $response->is_selected ? 'bg-green-50' : 'hover:bg-gray-50' }}">
                        <td class="px-4 py-3 font-medium">{{ optional($response->vendor)->name }}</td>
                        <td class="px-4 py-3 text-right">${{ number_format($response->total_amount, 2) }}</td>
                        <td class="px-4 py-3 text-center">{{ $response->delivery_days ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $response->payment_terms ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">{{ $response->is_selected ? '✓' : '' }}</td>
                        <td class="px-4 py-3">
                            @if($rfq->status?->value === 'closed' && !$rfq->responses->where('is_selected', true)->count())
                            <form method="POST" action="{{ route('procurement.rfqs.award', $rfq) }}">@csrf
                                <input type="hidden" name="response_id" value="{{ $response->id }}">
                                <button class="text-xs text-green-600 hover:underline">Award</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>
