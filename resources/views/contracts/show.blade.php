<x-app-layout>
    <x-slot name="title">{{ $contract->contract_number }}</x-slot>
    <div class="py-6 max-w-3xl">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $contract->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $contract->contract_number }} · {{ optional($contract->vendor)->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('contracts.pdf', $contract) }}"
                       class="px-3 py-1.5 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700">
                        Download PDF
                    </a>
                    <x-status-badge :status="$contract->status" />
                    @if($contract->status?->value === 'draft')
                    <form method="POST" action="{{ route('contracts.approve', $contract) }}">@csrf
                        <button class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md">Approve</button>
                    </form>
                    @endif
                    @if($contract->status?->value === 'active')
                    <form method="POST" action="{{ route('contracts.terminate', $contract) }}" onsubmit="return confirm('Terminate this contract?')">@csrf
                        <button class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-md">Terminate</button>
                    </form>
                    @endif
                    <a href="{{ route('contracts.edit', $contract) }}" class="px-3 py-1.5 border rounded-md text-sm text-gray-700">Edit</a>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-6 text-sm">
                <div><p class="text-gray-500">Start Date</p><p class="font-medium">{{ optional($contract->start_date)->format('M d, Y') }}</p></div>
                <div><p class="text-gray-500">End Date</p><p class="font-medium">{{ optional($contract->end_date)->format('M d, Y') }}</p></div>
                <div><p class="text-gray-500">Value</p><p class="font-bold text-lg">${{ number_format($contract->value ?? 0, 2) }}</p></div>
            </div>
            @if($contract->terms)<div class="mt-4"><p class="text-sm text-gray-500 mb-1">Terms</p><p class="text-sm">{{ $contract->terms }}</p></div>@endif
        </div>
    </div>
</x-app-layout>
