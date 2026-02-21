<x-app-layout>
    <x-slot name="title">Vehicles</x-slot>
    <div class="py-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('fleet.vehicles.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ Add Vehicle</a>
        </div>
        @if($vehicles->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Reg #</th><th class="px-4 py-3 text-left">Make/Model</th><th class="px-4 py-3 text-left">Type</th><th class="px-4 py-3 text-left">Status</th><th class="px-4 py-3 text-right">Mileage</th><th class="px-4 py-3 text-left">Insurance Exp.</th><th class="px-4 py-3"></th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($vehicles as $vehicle)
                    @php $insExpiring = $vehicle->insurance_expiry && $vehicle->insurance_expiry->diffInDays(now()) < 30 && $vehicle->insurance_expiry->isFuture(); @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono font-semibold">{{ $vehicle->registration_number }}</td>
                        <td class="px-4 py-3">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->year }})</td>
                        <td class="px-4 py-3 text-gray-500">{{ $vehicle->type }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$vehicle->status" /></td>
                        <td class="px-4 py-3 text-right">{{ number_format($vehicle->mileage) }} km</td>
                        <td class="px-4 py-3 {{ $insExpiring ? 'text-orange-600 font-semibold' : 'text-gray-500' }}">{{ optional($vehicle->insurance_expiry)->format('M d, Y') ?? '—' }}</td>
                        <td class="px-4 py-3"><a href="{{ route('fleet.vehicles.show', $vehicle) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $vehicles->links() }}</div>
        @else
        <x-empty-state title="No vehicles" action-url="{{ route('fleet.vehicles.create') }}" action-label="Add Vehicle" />
        @endif
    </div>
</x-app-layout>
