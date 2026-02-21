<x-app-layout>
    <x-slot name="title">Budgets</x-slot>
    <div class="py-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('budgets.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New Budget</a>
        </div>
        @if($budgets->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Department</th>
                        <th class="px-4 py-3 text-left">Fiscal Year</th>
                        <th class="px-4 py-3 text-left">Category</th>
                        <th class="px-4 py-3 text-right">Allocated</th>
                        <th class="px-4 py-3 text-right">Spent</th>
                        <th class="px-4 py-3 text-right">Remaining</th>
                        <th class="px-4 py-3">Utilization</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($budgets as $budget)
                    @php $pct = $budget->allocated_amount > 0 ? round($budget->spent_amount / $budget->allocated_amount * 100) : 0; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $budget->department }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $budget->fiscal_year }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($budget->category)->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">${{ number_format($budget->allocated_amount, 0) }}</td>
                        <td class="px-4 py-3 text-right">${{ number_format($budget->spent_amount, 0) }}</td>
                        <td class="px-4 py-3 text-right {{ $budget->remaining_amount < 0 ? 'text-red-600 font-bold' : 'text-green-700' }}">${{ number_format($budget->remaining_amount, 0) }}</td>
                        <td class="px-4 py-3 w-32">
                            <div class="h-2 bg-gray-200 rounded-full"><div class="h-2 {{ $pct > 90 ? 'bg-red-500' : ($pct > 70 ? 'bg-yellow-400' : 'bg-green-500') }} rounded-full" style="width:{{ min($pct, 100) }}%"></div></div>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $pct }}%</p>
                        </td>
                        <td class="px-4 py-3"><a href="{{ route('budgets.show', $budget) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <x-empty-state title="No budgets found" action-url="{{ route('budgets.create') }}" action-label="New Budget" />
        @endif
    </div>
</x-app-layout>
