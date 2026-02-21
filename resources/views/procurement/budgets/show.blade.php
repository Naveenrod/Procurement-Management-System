<x-app-layout>
    <x-slot name="title">Budget Detail</x-slot>
    <div class="py-6 max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ $budget->department }} — {{ $budget->fiscal_year }}</h2>
            @php $pct = $budget->allocated_amount > 0 ? round($budget->spent_amount / $budget->allocated_amount * 100) : 0; @endphp
            <div class="grid grid-cols-3 gap-4 mb-4 text-sm">
                <div><p class="text-gray-500">Allocated</p><p class="text-2xl font-bold">${{ number_format($budget->allocated_amount, 0) }}</p></div>
                <div><p class="text-gray-500">Spent</p><p class="text-2xl font-bold text-orange-600">${{ number_format($budget->spent_amount, 0) }}</p></div>
                <div><p class="text-gray-500">Remaining</p><p class="text-2xl font-bold {{ $budget->remaining_amount < 0 ? 'text-red-600' : 'text-green-600' }}">${{ number_format($budget->remaining_amount, 0) }}</p></div>
            </div>
            <div class="h-4 bg-gray-200 rounded-full mb-1"><div class="h-4 {{ $pct > 90 ? 'bg-red-500' : ($pct > 70 ? 'bg-yellow-400' : 'bg-green-500') }} rounded-full" style="width:{{ min($pct, 100) }}%"></div></div>
            <p class="text-sm text-gray-500">{{ $pct }}% utilized</p>
            @if($budget->category)<p class="mt-4 text-sm text-gray-600">Category: {{ $budget->category->name }}</p>@endif
        </div>
        <div class="flex justify-end mt-4">
            <a href="{{ route('budgets.edit', $budget) }}" class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Edit</a>
        </div>
    </div>
</x-app-layout>
