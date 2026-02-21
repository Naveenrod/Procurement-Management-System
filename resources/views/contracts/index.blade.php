<x-app-layout>
    <x-slot name="title">Contracts</x-slot>
    <div class="py-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Contracts</h2>
            <a href="{{ route('contracts.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New Contract</a>
        </div>
        @if($contracts->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Contract #</th>
                        <th class="px-4 py-3 text-left">Vendor</th>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-left">Start</th>
                        <th class="px-4 py-3 text-left">End</th>
                        <th class="px-4 py-3 text-right">Value</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($contracts as $contract)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $contract->contract_number }}</td>
                        <td class="px-4 py-3">{{ optional($contract->vendor)->name }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ Str::limit($contract->title, 35) }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($contract->start_date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($contract->end_date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">${{ number_format($contract->value, 0) }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$contract->status" /></td>
                        <td class="px-4 py-3"><a href="{{ route('contracts.show', $contract) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $contracts->links() }}</div>
        @else
        <x-empty-state title="No contracts found" action-url="{{ route('contracts.create') }}" action-label="New Contract" />
        @endif
    </div>
</x-app-layout>
