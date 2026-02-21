<x-app-layout>
    <x-slot name="title">Fuel Logs</x-slot>
    <div class="py-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('fleet.fuel-logs.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ Log Fuel</a>
        </div>
        @if($logs->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr><th class="px-4 py-3 text-left">Vehicle</th><th class="px-4 py-3 text-left">Date</th><th class="px-4 py-3 text-left">Station</th><th class="px-4 py-3 text-right">Liters</th><th class="px-4 py-3 text-right">$/Liter</th><th class="px-4 py-3 text-right">Total Cost</th><th class="px-4 py-3 text-right">Odometer</th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ optional($log->vehicle)->registration_number }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($log->filled_at)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $log->station_name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($log->liters, 2) }}</td>
                        <td class="px-4 py-3 text-right">${{ number_format($log->cost_per_liter, 3) }}</td>
                        <td class="px-4 py-3 text-right font-medium">${{ number_format($log->total_cost, 2) }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($log->odometer_reading) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $logs->links() }}</div>
        @else
        <x-empty-state title="No fuel logs" action-url="{{ route('fleet.fuel-logs.create') }}" action-label="Log Fuel" />
        @endif
    </div>
</x-app-layout>
