@extends('layouts.supplier')
@section('title', $rfq->rfq_number)
@section('content')
<div class="py-6 max-w-4xl space-y-4">
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h2 class="text-xl font-bold text-gray-800">{{ $rfq->title }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $rfq->rfq_number }} · Closes {{ optional($rfq->closing_date)->format('M d, Y') }}</p>
        @if($rfq->description)<p class="mt-3 text-sm text-gray-600">{{ $rfq->description }}</p>@endif
    </div>

    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Items Requested</h3></div>
        <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Product</th><th class="px-4 py-3 text-right">Quantity</th><th class="px-4 py-3 text-left">Specifications</th></tr></thead>
        <tbody class="divide-y">
            @foreach($rfq->items as $item)
            <tr><td class="px-4 py-3">{{ optional($item->product)->name }}</td><td class="px-4 py-3 text-right">{{ $item->quantity }}</td><td class="px-4 py-3 text-gray-500 text-xs">{{ $item->specifications ?? '—' }}</td></tr>
            @endforeach
        </tbody></table>
    </div>

    @if($rfq->status === 'published' && !$myResponse)
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Submit Your Response</h3>
        <form method="POST" action="{{ route('supplier.rfqs.respond', $rfq) }}">
            @csrf
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount *</label>
                    <input type="number" name="total_amount" step="0.01" required class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Days</label>
                    <input type="number" name="delivery_days" class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Terms</label>
                    <input type="text" name="payment_terms" placeholder="e.g. Net 30" class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm"></textarea>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Submit Response</button>
        </form>
    </div>
    @elseif($myResponse)
    <div class="bg-green-50 border border-green-200 rounded-lg p-5">
        <p class="text-green-800 font-medium">✓ Response submitted — ${{ number_format($myResponse->total_amount, 2) }}</p>
        @if($myResponse->notes)<p class="text-sm text-green-600 mt-1">{{ $myResponse->notes }}</p>@endif
    </div>
    @endif
</div>
@endsection
