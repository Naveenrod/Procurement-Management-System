<x-app-layout>
    <x-slot name="title">Fuel Log Detail</x-slot>
    <div class="py-6 max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Fuel Log</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ optional($fuelLog->vehicle)->registration_number }}</p>
                </div>
                <a href="{{ route('fleet.fuel-logs.edit', $fuelLog) }}" class="px-3 py-1.5 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Edit</a>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                <div>
                    <p class="text-gray-500">Vehicle</p>
                    <p class="font-medium">{{ optional($fuelLog->vehicle)->registration_number ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Trip</p>
                    <p class="font-medium">{{ optional($fuelLog->trip)->trip_number ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Fuel Type</p>
                    <p class="font-medium">{{ ucfirst($fuelLog->fuel_type ?? '—') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Liters</p>
                    <p class="font-medium">{{ number_format($fuelLog->liters ?? 0, 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Cost per Liter</p>
                    <p class="font-medium">${{ number_format($fuelLog->cost_per_liter ?? 0, 3) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Total Cost</p>
                    <p class="font-medium">${{ number_format($fuelLog->total_cost ?? 0, 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Odometer Reading</p>
                    <p class="font-medium">{{ number_format($fuelLog->odometer_reading ?? 0, 1) }} km</p>
                </div>
                <div>
                    <p class="text-gray-500">Filled At</p>
                    <p class="font-medium">{{ optional($fuelLog->filled_at)->format('M d, Y H:i') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Station Name</p>
                    <p class="font-medium">{{ $fuelLog->station_name ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
