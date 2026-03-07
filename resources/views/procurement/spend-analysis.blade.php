<x-app-layout>
    <x-slot name="title">Spend Analysis</x-slot>
    @php
        $totalSpend   = collect($spendByMonth ?? [])->sum('total');
        $vendorCount  = collect($spendByVendor ?? [])->count();
        $catCount     = collect($spendByCategory ?? [])->count();
        $activeMonths = collect($spendByMonth ?? [])->where('total', '>', 0)->count();
        $avgMonthly   = $activeMonths > 0 ? $totalSpend / $activeMonths : 0;
        $topVendor    = collect($spendByVendor ?? [])->first();
        $maxVendor    = collect($spendByVendor ?? [])->max('total') ?: 1;
        $chartColors  = ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'];
    @endphp

    <div class="py-6 space-y-5 w-full">

        {{-- ── FILTER BAR ─────────────────────────────────────────────────────── --}}
        <form method="GET" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <div class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">From</label>
                    <input type="date" name="from"
                           value="{{ request('from', now()->startOfYear()->format('Y-m-d')) }}"
                           class="border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1.5">To</label>
                    <input type="date" name="to"
                           value="{{ request('to', now()->format('Y-m-d')) }}"
                           class="border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <button type="submit"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    Apply
                </button>
            </div>
        </form>

        {{-- ── 4 STAT CARDS ───────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Spend</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">${{ number_format($totalSpend, 0) }}</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Last 12 months</p>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Avg Monthly</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">${{ number_format($avgMonthly, 0) }}</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Per active month</p>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Top Vendor</p>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mt-1 truncate">
                            {{ $topVendor ? Str::limit($topVendor->vendor_name, 16) : '—' }}
                        </h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                            {{ $topVendor ? '$'.number_format($topVendor->total, 0) : 'No data' }}
                        </p>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Categories</p>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1 tabular-nums">{{ $catCount }}</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">With spend activity</p>
                    </div>
                    <div class="w-11 h-11 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 ml-3">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── ROW 2 : SPEND BY MONTH BAR + SPEND BY CATEGORY DOUGHNUT ─────────── --}}
        <div class="grid grid-cols-1 xl:grid-cols-[2fr_1fr] gap-5">

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Spend by Month</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">Last 12 months</span>
                </div>
                <div class="relative" style="height:220px;">
                    <canvas id="monthChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Spend by Category</h3>
                </div>
                <div class="relative" style="height:160px;">
                    <canvas id="categoryChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach(collect($spendByCategory ?? [])->take(5) as $i => $cat)
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $chartColors[$i] ?? '#94a3b8' }}"></span>
                            <span class="text-gray-600 dark:text-gray-400 truncate">{{ $cat->name }}</span>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-gray-200 ml-2 flex-shrink-0">${{ number_format($cat->total, 0) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- ── ROW 3 : VENDOR PROGRESS BARS + VENDOR TABLE ────────────────────── --}}
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_2fr] gap-5">

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Top Vendors</h3>
                    <span class="text-xs text-gray-400 dark:text-gray-500">By total spend</span>
                </div>
                <div class="space-y-4">
                    @forelse(collect($spendByVendor ?? [])->take(8) as $vendor)
                    @php $pct = $maxVendor > 0 ? min(100, ($vendor->total / $maxVendor) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-sm text-gray-700 dark:text-gray-300 font-medium truncate mr-2">{{ Str::limit($vendor->vendor_name, 20) }}</span>
                            <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 flex-shrink-0">
                                ${{ $vendor->total >= 1000 ? number_format($vendor->total/1000, 0).'k' : number_format($vendor->total, 0) }}
                            </span>
                        </div>
                        <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-1.5 bg-indigo-500 rounded-full" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 dark:text-gray-500">No vendor data available.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Spend by Vendor — Detail</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-50 dark:border-gray-700">
                                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">#</th>
                                <th class="text-left py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Vendor</th>
                                <th class="text-right py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Total Spend</th>
                                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Orders</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @forelse($spendByVendor ?? [] as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-5 py-3.5 text-xs font-semibold text-gray-400 dark:text-gray-500">{{ $loop->iteration }}</td>
                                <td class="py-3.5 font-semibold text-gray-800 dark:text-gray-200">{{ $row->vendor_name }}</td>
                                <td class="py-3.5 text-right font-bold text-gray-800 dark:text-gray-100 tabular-nums">${{ number_format($row->total, 2) }}</td>
                                <td class="px-5 py-3.5 text-right text-gray-500 dark:text-gray-400 tabular-nums">{{ number_format($row->count) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400 dark:text-gray-500">No spend data available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if(collect($spendByVendor ?? [])->count())
                        <tfoot>
                            <tr class="border-t-2 border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30">
                                <td class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Total</td>
                                <td class="py-3"></td>
                                <td class="py-3 text-right font-bold text-gray-800 dark:text-gray-100 tabular-nums">${{ number_format(collect($spendByVendor)->sum('total'), 2) }}</td>
                                <td class="px-5 py-3 text-right font-bold text-gray-600 dark:text-gray-300 tabular-nums">{{ number_format(collect($spendByVendor)->sum('count')) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

        </div>

    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark    = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
        const textColor = isDark ? '#9ca3af' : '#6b7280';
        const fmtMoney  = v => '$' + (v >= 1000 ? Math.round(v / 1000) + 'k' : v);

        // ── Spend by Month bar chart ──────────────────────────────────────────
        new Chart(document.getElementById('monthChart'), {
            type: 'bar',
            data: {
                labels: @json(collect($spendByMonth ?? [])->pluck('month')),
                datasets: [{
                    data: @json(collect($spendByMonth ?? [])->pluck('total')),
                    backgroundColor: '#6366f1',
                    hoverBackgroundColor: '#4f46e5',
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: ctx => ' $' + Number(ctx.parsed.y).toLocaleString() }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { size: 10 }, maxRotation: 45 }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: { color: textColor, font: { size: 10 }, callback: fmtMoney }
                    }
                }
            }
        });

        // ── Spend by Category doughnut ────────────────────────────────────────
        const catColors = @json($chartColors);
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: @json(collect($spendByCategory ?? [])->pluck('name')),
                datasets: [{
                    data: @json(collect($spendByCategory ?? [])->pluck('total')),
                    backgroundColor: catColors.slice(0, {{ max(1, count($spendByCategory ?? [])) }}),
                    borderWidth: 0,
                    hoverOffset: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: ctx => ' ' + ctx.label + ': $' + Number(ctx.parsed).toLocaleString() }
                    }
                }
            }
        });
    });
    </script>
    @endpush
</x-app-layout>
