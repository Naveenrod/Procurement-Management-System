<x-app-layout>
    <x-slot name="title">Fleet Dashboard</x-slot>
    @php
        $stats       = $stats ?? [];
        $totalVeh    = $stats['total_vehicles'] ?? 0;
        $availVeh    = $stats['available_vehicles'] ?? 0;
        $inUseVeh    = $stats['in_use_vehicles'] ?? 0;
        $maintVeh    = $stats['maintenance_vehicles'] ?? 0;
        $fuelCost    = $stats['fuel_cost_this_month'] ?? 0;
        $maintDue    = $stats['maintenance_due'] ?? 0;
        $activeTrips = $activeTrips ?? collect();
        $recentTrips = $recentTrips ?? collect();
    @endphp

    <div class="py-6 space-y-5 w-full">

        {{-- ── 4 STAT CARDS ──────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

            {{-- Available Vehicles --}}
            <a href="{{ route('fleet.vehicles.index') }}"
               class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition-shadow block">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Available</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">{{ $availVeh }}</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">of {{ $totalVeh }} vehicles</p>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Active Trips --}}
            <a href="{{ route('fleet.trips.index') }}"
               class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition-shadow block">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Active Trips</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">{{ $stats['active_trips'] ?? 0 }}</h3>
                        @if(($stats['active_trips'] ?? 0) > 0)
                            <p class="text-xs font-semibold text-indigo-500 mt-2">In progress</p>
                        @else
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">No active trips</p>
                        @endif
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Maintenance Due --}}
            <a href="{{ route('fleet.maintenance.index') }}"
               class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition-shadow block">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Maintenance Due</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">{{ $maintDue }}</h3>
                        @if($maintDue > 0)
                            <p class="text-xs font-semibold text-amber-500 mt-2">Within 7 days</p>
                        @else
                            <p class="text-xs font-semibold text-emerald-500 mt-2">All clear</p>
                        @endif
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Fuel This Month --}}
            <a href="{{ route('fleet.fuel-logs.index') }}"
               class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition-shadow block">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Fuel This Month</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">${{ number_format($fuelCost, 0) }}</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ now()->format('F Y') }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                    </div>
                </div>
            </a>

        </div>

        {{-- ── ROW 2: ACTIVE TRIPS TABLE + FLEET STATUS SIDEBAR ──────────────── --}}
        <div class="grid grid-cols-1 xl:grid-cols-[2fr_1fr] gap-5">

            {{-- Active Trips Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Active Trips</h3>
                    <a href="{{ route('fleet.trips.index') }}"
                       class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition-colors">
                        View all
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                @if($activeTrips->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-50 dark:border-gray-700">
                                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Trip #</th>
                                <th class="text-left py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Vehicle</th>
                                <th class="text-left py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Driver</th>
                                <th class="text-left py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide hidden sm:table-cell">Route</th>
                                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Started</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach($activeTrips as $trip)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('fleet.trips.show', $trip) }}"
                                       class="font-mono text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $trip->trip_number }}
                                    </a>
                                </td>
                                <td class="py-3.5 font-medium text-gray-800 dark:text-gray-200">
                                    {{ optional($trip->vehicle)->registration_number ?? '—' }}
                                </td>
                                <td class="py-3.5 text-gray-600 dark:text-gray-300">
                                    {{ optional($trip->driver)->name ?? '—' }}
                                </td>
                                <td class="py-3.5 text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                                    {{ optional($trip->route)->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-right text-gray-500 dark:text-gray-400">
                                    {{ optional($trip->started_at)->format('H:i') ?? '—' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="px-5 py-12 text-center">
                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No active trips right now</p>
                    <a href="{{ route('fleet.trips.index') }}"
                       class="mt-3 inline-flex items-center text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                        View all trips →
                    </a>
                </div>
                @endif
            </div>

            {{-- Fleet Status Sidebar --}}
            <div class="space-y-5">

                {{-- Vehicle Status Breakdown --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Fleet Status</h3>
                        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $totalVeh }} total</span>
                    </div>
                    <div class="space-y-4">
                        @php $pctAvail = $totalVeh > 0 ? round($availVeh / $totalVeh * 100) : 0; @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Available</span>
                                <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">{{ $availVeh }} <span class="text-xs text-gray-400 font-normal">({{ $pctAvail }}%)</span></span>
                            </div>
                            <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-2 bg-emerald-500 rounded-full" style="width:{{ $pctAvail }}%"></div>
                            </div>
                        </div>

                        @php $pctInUse = $totalVeh > 0 ? round($inUseVeh / $totalVeh * 100) : 0; @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">In Use</span>
                                <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">{{ $inUseVeh }} <span class="text-xs text-gray-400 font-normal">({{ $pctInUse }}%)</span></span>
                            </div>
                            <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-2 bg-indigo-500 rounded-full" style="width:{{ $pctInUse }}%"></div>
                            </div>
                        </div>

                        @php $pctMaint = $totalVeh > 0 ? round($maintVeh / $totalVeh * 100) : 0; @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Maintenance</span>
                                <span class="text-sm font-semibold text-amber-600 dark:text-amber-400">{{ $maintVeh }} <span class="text-xs text-gray-400 font-normal">({{ $pctMaint }}%)</span></span>
                            </div>
                            <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-2 bg-amber-500 rounded-full" style="width:{{ $pctMaint }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Completed Trips This Month + Quick Links --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-4">This Month</h3>
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 tabular-nums">{{ $stats['completed_trips_this_month'] ?? 0 }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Trips completed</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-2">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">Quick Links</p>
                        <a href="{{ route('fleet.vehicles.index') }}" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors py-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Vehicles
                        </a>
                        <a href="{{ route('fleet.drivers.index') }}" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors py-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Drivers
                        </a>
                        <a href="{{ route('fleet.maintenance.index') }}" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors py-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Maintenance
                        </a>
                        <a href="{{ route('fleet.fuel-logs.index') }}" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors py-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                            Fuel Logs
                        </a>
                    </div>
                </div>

            </div>

        </div>

        {{-- ── ROW 3: RECENTLY COMPLETED TRIPS ──────────────────────────────── --}}
        @if($recentTrips->count())
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                    Recently Completed — {{ now()->format('F Y') }}
                </h3>
                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $stats['completed_trips_this_month'] ?? 0 }} this month</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-700">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Trip #</th>
                            <th class="text-left py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Vehicle</th>
                            <th class="text-left py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Driver</th>
                            <th class="text-left py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide hidden sm:table-cell">Route</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Completed</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @foreach($recentTrips as $trip)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('fleet.trips.show', $trip) }}"
                                   class="font-mono text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $trip->trip_number }}
                                </a>
                            </td>
                            <td class="py-3.5 font-medium text-gray-800 dark:text-gray-200">{{ optional($trip->vehicle)->registration_number ?? '—' }}</td>
                            <td class="py-3.5 text-gray-600 dark:text-gray-300">{{ optional($trip->driver)->name ?? '—' }}</td>
                            <td class="py-3.5 text-gray-500 dark:text-gray-400 hidden sm:table-cell">{{ optional($trip->route)->name ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-right text-gray-500 dark:text-gray-400">
                                {{ optional($trip->completed_at)->format('d M H:i') ?? '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
