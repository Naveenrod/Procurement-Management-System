<x-app-layout>
    <x-slot name="title">Request for Quotations</x-slot>
    <div class="py-6">
        <div class="flex justify-between items-center mb-4">
            <form method="GET" class="flex gap-2">
                <select name="status" class="border rounded-md px-3 py-1.5 text-sm">
                    <option value="">All Statuses</option>
                    @foreach(\App\Enums\RfqStatus::cases() as $s)
                    <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
                <button class="px-3 py-1.5 bg-gray-100 rounded-md text-sm">Filter</button>
            </form>
            <a href="{{ route('rfqs.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New RFQ</a>
        </div>
        @if($rfqs->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">RFQ Number</th>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-left">Closing Date</th>
                        <th class="px-4 py-3 text-center">Vendors</th>
                        <th class="px-4 py-3 text-center">Responses</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($rfqs as $rfq)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $rfq->rfq_number }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ Str::limit($rfq->title, 40) }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($rfq->closing_date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-center">{{ $rfq->vendors->count() }}</td>
                        <td class="px-4 py-3 text-center">{{ $rfq->responses->count() }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$rfq->status" /></td>
                        <td class="px-4 py-3"><a href="{{ route('rfqs.show', $rfq) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $rfqs->links() }}</div>
        @else
        <x-empty-state title="No RFQs found" action-url="{{ route('rfqs.create') }}" action-label="New RFQ" />
        @endif
    </div>
</x-app-layout>
