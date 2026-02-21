<x-app-layout>
    <x-slot name="title">Drivers</x-slot>
    <div class="py-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('fleet.drivers.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ Add Driver</a>
        </div>
        @if($drivers->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Employee ID</th><th class="px-4 py-3 text-left">Name</th><th class="px-4 py-3 text-left">Phone</th><th class="px-4 py-3 text-left">Status</th><th class="px-4 py-3 text-left">License Expiry</th><th class="px-4 py-3"></th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($drivers as $driver)
                    @php $licExpiring = $driver->license_expiry && $driver->license_expiry->diffInDays(now()) < 30 && $driver->license_expiry->isFuture(); @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $driver->employee_id }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $driver->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $driver->phone }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$driver->status" /></td>
                        <td class="px-4 py-3 {{ $licExpiring ? 'text-orange-600 font-semibold' : 'text-gray-500' }}">{{ optional($driver->license_expiry)->format('M d, Y') ?? '—' }}</td>
                        <td class="px-4 py-3"><a href="{{ route('fleet.drivers.show', $driver) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $drivers->links() }}</div>
        @else
        <x-empty-state title="No drivers" action-url="{{ route('fleet.drivers.create') }}" action-label="Add Driver" />
        @endif
    </div>
</x-app-layout>
