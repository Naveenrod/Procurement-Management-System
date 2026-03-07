<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    @php
        $stats           = $stats ?? [];
        $monthLabels     = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $currentYear     = now()->year;
        $prevYear        = $currentYear - 1;
        $maxVendorSpend  = $spendByVendor->count() ? $spendByVendor->max('total') : 1;
        $totalPoCount    = $poStatusDistribution->sum('count');
        $currMonthSpend  = !empty($spendChart) ? $spendChart[count($spendChart)-1]['total'] : 0;
        $prevMonthSpend  = count($spendChart) >= 2 ? $spendChart[count($spendChart)-2]['total'] : 0;
    @endphp

    <div class="py-6 space-y-5 w-full">

        {{-- =====================================================================
             ROW 1 : 2×2 STAT CARDS  +  MONTHLY SPEND BAR CHART
        ====================================================================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-[5fr_7fr] gap-5">

            {{-- ---- 2×2 STAT CARDS ------------------------------------------- --}}
            <div class="grid grid-cols-2 gap-4 content-start">

                @hasanyrole('admin')

                {{-- Card: Purchase Orders --}}
                <a href="{{ route('procurement.purchase-orders.index') }}"
                   class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition-shadow block">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Purchase Orders</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">{{ number_format($stats['total_pos'] ?? 0) }}</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Total all time</p>
                        </div>
                        <div class="w-11 h-11 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Card: Pending Approvals --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pending Approvals</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">{{ number_format($stats['pending_approvals'] ?? 0) }}</h3>
                            @if(($stats['pending_approvals'] ?? 0) > 0)
                                <p class="text-xs font-semibold text-amber-500 mt-2">Action needed</p>
                            @else
                                <p class="text-xs font-semibold text-emerald-500 mt-2">All clear</p>
                            @endif
                        </div>
                        <div class="w-11 h-11 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Card: Spend This Month --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Spend This Month</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">${{ number_format($stats['total_spend_month'] ?? 0, 0) }}</h3>
                            <div class="flex items-center gap-1.5 mt-2">
                                @if(isset($spendChange) && $spendChange !== null)
                                    @if($spendChange >= 0)
                                        <span class="text-xs font-semibold text-emerald-500">↑ {{ $spendChange }}%</span>
                                    @else
                                        <span class="text-xs font-semibold text-red-500">↓ {{ abs($spendChange) }}%</span>
                                    @endif
                                    <span class="text-xs text-gray-400 dark:text-gray-500">vs last month</span>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">This month</span>
                                @endif
                            </div>
                        </div>
                        <div class="w-11 h-11 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Card: Active Vendors --}}
                <a href="{{ route('vendors.index') }}"
                   class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition-shadow block">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Active Vendors</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">{{ number_format($stats['active_vendors'] ?? 0) }}</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Approved suppliers</p>
                        </div>
                        <div class="w-11 h-11 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                </a>

                @endhasanyrole

                @hasanyrole('manager')

                {{-- Card: Open POs --}}
                <a href="{{ route('procurement.purchase-orders.index') }}"
                   class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition-shadow block">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Open Orders</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">{{ number_format($stats['open_pos'] ?? 0) }}</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">In progress</p>
                        </div>
                        <div class="w-11 h-11 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Card: Pending Approvals --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pending Approvals</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">{{ number_format($stats['pending_approvals'] ?? 0) }}</h3>
                            @if(($stats['pending_approvals'] ?? 0) > 0)
                                <p class="text-xs font-semibold text-amber-500 mt-2">Action needed</p>
                            @else
                                <p class="text-xs font-semibold text-emerald-500 mt-2">All clear</p>
                            @endif
                        </div>
                        <div class="w-11 h-11 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Card: Spend This Month --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Spend This Month</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">${{ number_format($stats['total_spend_month'] ?? 0, 0) }}</h3>
                            <div class="flex items-center gap-1.5 mt-2">
                                @if(isset($spendChange) && $spendChange !== null)
                                    @if($spendChange >= 0)
                                        <span class="text-xs font-semibold text-emerald-500">↑ {{ $spendChange }}%</span>
                                    @else
                                        <span class="text-xs font-semibold text-red-500">↓ {{ abs($spendChange) }}%</span>
                                    @endif
                                    <span class="text-xs text-gray-400 dark:text-gray-500">vs last month</span>
                                @endif
                            </div>
                        </div>
                        <div class="w-11 h-11 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Card: Pending Invoices --}}
                <a href="{{ route('procurement.invoices.index') }}"
                   class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition-shadow block">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pending Invoices</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">{{ number_format($stats['pending_invoices'] ?? 0) }}</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Awaiting payment</p>
                        </div>
                        <div class="w-11 h-11 rounded-lg bg-red-50 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"/>
                            </svg>
                        </div>
                    </div>
                </a>

                @endhasanyrole

                @hasanyrole('buyer')

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <div><p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">My Requisitions</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $stats['my_requisitions'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-400 mt-2">All time</p></div>
                        <div class="w-11 h-11 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <div><p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Drafts</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $stats['draft_requisitions'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-400 mt-2">Not submitted</p></div>
                        <div class="w-11 h-11 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <div><p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Approved</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $stats['approved_requisitions'] ?? 0 }}</h3>
                            <p class="text-xs font-semibold text-emerald-500 mt-2">Ready to order</p></div>
                        <div class="w-11 h-11 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>

                <a href="{{ route('procurement.purchase-orders.index') }}"
                   class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition-shadow block">
                    <div class="flex items-start justify-between">
                        <div><p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Open POs</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $stats['open_pos'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-400 mt-2">In progress</p></div>
                        <div class="w-11 h-11 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                    </div>
                </a>

                @endhasanyrole

                @hasanyrole('warehouse_worker')

                <a href="{{ route('warehouse.receiving.index') }}"
                   class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition-shadow block">
                    <div class="flex items-start justify-between">
                        <div><p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">To Process</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $stats['orders_to_process'] ?? 0 }}</h3>
                            <p class="text-xs text-amber-500 font-semibold mt-2">Pending</p></div>
                        <div class="w-11 h-11 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        </div>
                    </div>
                </a>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <div><p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">To Receive</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $stats['orders_to_receive'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-400 mt-2">Inbound</p></div>
                        <div class="w-11 h-11 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <div><p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">To Pick</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $stats['orders_to_pick'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-400 mt-2">Outbound</p></div>
                        <div class="w-11 h-11 rounded-lg bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                        </div>
                    </div>
                </div>

                <a href="{{ route('inventory.reorders.index') }}"
                   class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition-shadow block">
                    <div class="flex items-start justify-between">
                        <div><p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Low Stock</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $stats['low_stock_items'] ?? 0 }}</h3>
                            @if(($stats['low_stock_items'] ?? 0) > 0)
                                <p class="text-xs font-semibold text-red-500 mt-2">Reorder needed</p>
                            @else
                                <p class="text-xs font-semibold text-emerald-500 mt-2">All stocked</p>
                            @endif
                        </div>
                        <div class="w-11 h-11 rounded-lg bg-red-50 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                </a>

                @endhasanyrole

            </div>{{-- end stat grid --}}

            {{-- ---- MONTHLY SPEND BAR CHART ----------------------------------- --}}
            @if(!empty($spendCurrentYear))
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                        Spend — {{ $currentYear }} vs {{ $prevYear }}
                    </h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">Monthly comparison</span>
                </div>
                <div class="relative" style="height:210px;">
                    <canvas id="monthlyBarChart"></canvas>
                </div>
            </div>
            @endif

        </div>{{-- end row 1 --}}

        {{-- =====================================================================
             ROW 2 : SPEND TREND LINE CHART  +  SPEND BY VENDOR
        ====================================================================== --}}
        @if(!empty($spendCurrentYear))
        <div class="grid grid-cols-1 xl:grid-cols-[2fr_1fr] gap-5">

            {{-- Spend Trend Area Chart --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Spend Trend</h3>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-0.5 bg-indigo-500 inline-block rounded-full"></span>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $currentYear }}
                                <span class="font-bold text-gray-800 dark:text-gray-100 ml-1">${{ number_format($currMonthSpend, 0) }}</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-0.5 bg-emerald-400 inline-block rounded-full"></span>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $prevYear }}
                                <span class="font-bold text-gray-800 dark:text-gray-100 ml-1">${{ number_format($prevMonthSpend, 0) }}</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="relative" style="height:200px;">
                    <canvas id="spendTrendChart"></canvas>
                </div>
            </div>

            {{-- Spend by Vendor progress bars --}}
            @if($spendByVendor->count())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Top Vendors by Spend</h3>
                </div>
                <div class="space-y-4">
                    @foreach($spendByVendor as $vendor)
                    @php $pct = $maxVendorSpend > 0 ? min(100, ($vendor->total / $maxVendorSpend) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-sm text-gray-700 dark:text-gray-300 font-medium truncate mr-2">{{ Str::limit($vendor->vendor_name, 22) }}</span>
                            <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 flex-shrink-0">
                                ${{ $vendor->total >= 1000 ? number_format($vendor->total/1000, 0).'k' : number_format($vendor->total, 0) }}
                            </span>
                        </div>
                        <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-1.5 bg-indigo-500 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>{{-- end row 2 --}}
        @endif

        {{-- =====================================================================
             ROW 3 : RECENT REQUISITIONS TABLE  +  PO STATUS DONUT  +  ACTIVITY
        ====================================================================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-[2fr_1fr_1fr] gap-5">

            {{-- Recent Requisitions Table --}}
            @if($recentRequisitions->count())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Recent Requisitions</h3>
                    <a href="{{ route('procurement.requisitions.index') }}"
                       class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition-colors">
                        View all
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-50 dark:border-gray-700">
                                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Requisition</th>
                                <th class="text-left py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Department</th>
                                <th class="text-left py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide hidden sm:table-cell">Date</th>
                                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach($recentRequisitions as $req)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm">{{ $req->requisition_number }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($req->title ?? '', 32) }}</p>
                                </td>
                                <td class="py-3.5 text-sm text-gray-500 dark:text-gray-400">{{ $req->department ?? '—' }}</td>
                                <td class="py-3.5 text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">{{ $req->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-3.5 text-right"><x-status-badge :status="$req->status" /></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- PO Status Donut --}}
            @if($poStatusDistribution->count())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">PO Status</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ number_format($totalPoCount) }} total</span>
                </div>
                <div class="relative" style="height:160px;">
                    <canvas id="poDonutChart"></canvas>
                </div>
                <div class="mt-4 space-y-2.5">
                    @php $donutPalette = ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899']; @endphp
                    @foreach($poStatusDistribution->take(4) as $i => $item)
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $donutPalette[$i] ?? '#94a3b8' }}"></span>
                            <span class="text-gray-600 dark:text-gray-400">{{ ucwords(str_replace('_', ' ', $item->status)) }}</span>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ number_format($item->count) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Pending Invoices / Recent Activity --}}
            @if(!empty($recentActivity))
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Recent Activity</h3>
                </div>
                <div class="space-y-4 overflow-y-auto" style="max-height:320px;">
                    @foreach($recentActivity as $activity)
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5
                            {{ $activity['type'] === 'requisition' ? 'bg-indigo-100 dark:bg-indigo-900/40' : 'bg-blue-100 dark:bg-blue-900/40' }}">
                            @if($activity['type'] === 'requisition')
                            <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            @else
                            <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 leading-snug">{{ $activity['description'] }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                {{ $activity['user'] }} · {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @elseif($pendingInvoices->count())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Pending Invoices</h3>
                    <a href="{{ route('procurement.invoices.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">View all →</a>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @foreach($pendingInvoices as $inv)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <div class="min-w-0 mr-3">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $inv->invoice_number }}</p>
                            <p class="text-xs text-gray-400">{{ Str::limit(optional($inv->vendor)->name ?? '—', 18) }} · ${{ number_format($inv->total_amount, 0) }}</p>
                        </div>
                        <x-status-badge :status="$inv->status" />
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>{{-- end row 3 --}}

    </div>{{-- end page --}}

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark     = document.documentElement.classList.contains('dark');
        const gridColor  = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
        const textColor  = isDark ? '#9ca3af' : '#6b7280';
        const monthLabels = @json($monthLabels);

        const fmtMoney = v => '$' + (v >= 1000000 ? (v/1000000).toFixed(1)+'M' : v >= 1000 ? Math.round(v/1000)+'k' : v);

        // ── Monthly Bar Chart (row 1 right) ──────────────────────────────────
        @if(!empty($spendCurrentYear))
        (function () {
            const ctx = document.getElementById('monthlyBarChart');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [
                        {
                            label: '{{ $currentYear }}',
                            data: @json($spendCurrentYear),
                            backgroundColor: '#6366f1',
                            borderRadius: 3,
                            barThickness: 7,
                        },
                        {
                            label: '{{ $prevYear }}',
                            data: @json($spendPrevYear),
                            backgroundColor: isDark ? 'rgba(99,102,241,0.25)' : 'rgba(99,102,241,0.18)',
                            borderRadius: 3,
                            barThickness: 7,
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: { boxWidth: 10, padding: 14, font: { size: 11 }, color: textColor }
                        },
                        tooltip: {
                            callbacks: { label: ctx => ' ' + ctx.dataset.label + ': ' + fmtMoney(ctx.parsed.y) }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 10 } }
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { size: 10 }, callback: fmtMoney }
                        }
                    }
                }
            });
        })();

        // ── Spend Trend Line Chart (row 2 left) ───────────────────────────────
        (function () {
            const ctx = document.getElementById('spendTrendChart');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [
                        {
                            label: '{{ $currentYear }}',
                            data: @json($spendCurrentYear),
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99,102,241,0.08)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2.5,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: '#6366f1',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2,
                        },
                        {
                            label: '{{ $prevYear }}',
                            data: @json($spendPrevYear),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.04)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2.5,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: '#10b981',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: { label: ctx => ' ' + ctx.dataset.label + ': ' + fmtMoney(ctx.parsed.y) }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 10 } }
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { size: 10 }, callback: fmtMoney }
                        }
                    }
                }
            });
        })();
        @endif

        // ── PO Status Donut (row 3 middle) ────────────────────────────────────
        @if($poStatusDistribution->count())
        (function () {
            const ctx = document.getElementById('poDonutChart');
            if (!ctx) return;
            const donutColors = ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'];
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json(collect($poStatusDistribution)->pluck('status')->map(fn($s) => ucwords(str_replace('_', ' ', $s)))->values()),
                    datasets: [{
                        data: @json(collect($poStatusDistribution)->pluck('count')->values()),
                        backgroundColor: donutColors.slice(0, {{ $poStatusDistribution->count() }}),
                        borderWidth: 0,
                        hoverOffset: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '74%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' POs'
                            }
                        }
                    }
                }
            });
        })();
        @endif
    });
    </script>
    @endpush
</x-app-layout>
