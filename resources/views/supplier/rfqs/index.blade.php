@extends('layouts.supplier')
@section('title', 'RFQs')
@section('content')
<div class="py-6">
    @if($rfqs->count())
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3 text-left">RFQ #</th><th class="px-4 py-3 text-left">Title</th><th class="px-4 py-3 text-left">Closing Date</th><th class="px-4 py-3 text-left">Status</th><th class="px-4 py-3 text-left">My Response</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y">
                @foreach($rfqs as $rfq)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $rfq->rfq_number }}</td>
                    <td class="px-4 py-3 font-medium">{{ Str::limit($rfq->title, 40) }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ optional($rfq->closing_date)->format('M d, Y') }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$rfq->status" /></td>
                    <td class="px-4 py-3"><span class="text-xs text-gray-400">—</span></td>
                    <td class="px-4 py-3"><a href="{{ route('supplier.rfqs.show', $rfq) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <x-empty-state title="No RFQs assigned" description="RFQs invited to your company will appear here." />
    @endif
</div>
@endsection
