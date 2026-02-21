<x-app-layout>
    <x-slot name="title">Maintenance Record</x-slot>
    <div class="py-6 max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $record->type }}</h2>
            <p class="text-sm text-gray-500 mb-4">{{ optional($record->vehicle)->registration_number }}</p>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-gray-500">Scheduled Date</p><p class="font-medium">{{ optional($record->scheduled_date)->format('M d, Y') }}</p></div>
                <div><p class="text-gray-500">Completed Date</p><p class="font-medium">{{ optional($record->completed_date)->format('M d, Y') ?? '—' }}</p></div>
                <div><p class="text-gray-500">Cost</p><p class="font-medium">${{ number_format($record->cost ?? 0, 2) }}</p></div>
                <div><p class="text-gray-500">Performed By</p><p class="font-medium">{{ $record->performed_by ?? '—' }}</p></div>
                @if($record->description)<div class="col-span-2"><p class="text-gray-500">Description</p><p>{{ $record->description }}</p></div>@endif
            </div>
        </div>
    </div>
</x-app-layout>
