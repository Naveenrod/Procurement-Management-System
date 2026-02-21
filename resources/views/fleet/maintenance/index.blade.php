<x-app-layout>
    <x-slot name="title">Maintenance Records</x-slot>
    <div class="py-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('fleet.maintenance.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ Schedule Maintenance</a>
        </div>
        @if($records->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Vehicle</th><th class="px-4 py-3 text-left">Type</th><th class="px-4 py-3 text-left">Scheduled</th><th class="px-4 py-3 text-left">Completed</th><th class="px-4 py-3 text-right">Cost</th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($records as $record)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ optional($record->vehicle)->registration_number }}</td>
                        <td class="px-4 py-3 font-medium">{{ $record->type }}</td>
                        <td class="px-4 py-3 {{ !$record->completed_date && $record->scheduled_date < now() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">{{ optional($record->scheduled_date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($record->completed_date)->format('M d, Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">${{ number_format($record->cost ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $records->links() }}</div>
        @else
        <x-empty-state title="No maintenance records" action-url="{{ route('fleet.maintenance.create') }}" action-label="Schedule Maintenance" />
        @endif
    </div>
</x-app-layout>
