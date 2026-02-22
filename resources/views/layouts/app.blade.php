<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: true }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ProcureMS') }} - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
<div class="flex h-screen overflow-hidden">

    <aside x-show="sidebarOpen" x-transition:enter="transition-transform duration-200" class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-gray-700">
            <span class="text-xl font-bold">ProcureMS</span>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5 text-sm">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Dashboard</a>

            @hasanyrole('admin|manager|buyer')
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Procurement</p>
            <a href="{{ route('procurement.requisitions.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('procurement.requisitions.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Requisitions</a>
            <a href="{{ route('procurement.rfqs.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('procurement.rfqs.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">RFQs</a>
            <a href="{{ route('procurement.purchase-orders.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('procurement.purchase-orders.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Purchase Orders</a>
            <a href="{{ route('procurement.goods-receipts.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('procurement.goods-receipts.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Goods Receipts</a>
            <a href="{{ route('procurement.invoices.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('procurement.invoices.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Invoices</a>
            <a href="{{ route('procurement.budgets.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('procurement.budgets.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Budgets</a>
            <a href="{{ route('procurement.spend-analysis') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('procurement.spend-analysis') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Spend Analysis</a>
            @endhasanyrole

            @hasanyrole('admin|manager')
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Vendors</p>
            <a href="{{ route('vendors.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('vendors.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Vendors</a>
            <a href="{{ route('contracts.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('contracts.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Contracts</a>
            @endhasanyrole

            @hasanyrole('admin|manager|buyer|warehouse_worker')
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Inventory</p>
            <a href="{{ route('inventory.stock.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('inventory.stock.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Stock</a>
            <a href="{{ route('inventory.warehouses.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('inventory.warehouses.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Warehouses</a>
            <a href="{{ route('inventory.transfers.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('inventory.transfers.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Transfers</a>
            <a href="{{ route('inventory.shipments.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('inventory.shipments.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Shipments</a>
            <a href="{{ route('inventory.reorders.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('inventory.reorders.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Reorder Alerts</a>
            <a href="{{ route('inventory.cycle-counts.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('inventory.cycle-counts.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Cycle Counts</a>
            @endhasanyrole

            @hasanyrole('admin|manager|warehouse_worker')
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Warehouse (WMS)</p>
            <a href="{{ route('warehouse.orders.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('warehouse.orders.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Orders</a>
            <a href="{{ route('warehouse.receiving.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('warehouse.receiving.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Receiving</a>
            <a href="{{ route('warehouse.picking.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('warehouse.picking.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Picking</a>
            <a href="{{ route('warehouse.packing.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('warehouse.packing.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Packing</a>
            <a href="{{ route('warehouse.shipping.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('warehouse.shipping.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Shipping</a>
            <a href="{{ route('warehouse.scan.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('warehouse.scan.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Barcode Scan</a>
            @endhasanyrole

            @hasanyrole('admin|manager')
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fleet</p>
            <a href="{{ route('fleet.dashboard') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('fleet.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Fleet Dashboard</a>
            <a href="{{ route('fleet.vehicles.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('fleet.vehicles.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Vehicles</a>
            <a href="{{ route('fleet.drivers.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('fleet.drivers.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Drivers</a>
            <a href="{{ route('fleet.trips.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('fleet.trips.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Trips</a>
            <a href="{{ route('fleet.maintenance.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('fleet.maintenance.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Maintenance</a>
            <a href="{{ route('fleet.fuel-logs.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('fleet.fuel-logs.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">Fuel Logs</a>
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Reports</p>
            <a href="{{ route('reports.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">All Reports</a>
            @endhasanyrole
        </nav>
        <div class="p-3 border-t border-gray-700 text-xs text-gray-400 truncate">{{ auth()->user()->name }} &bull; {{ auth()->user()->roles->first()?->name ?? 'user' }}</div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center px-6 gap-4 flex-shrink-0 z-10 relative">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            @if(!request()->routeIs('dashboard'))
            <button onclick="history.back()" class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </button>
            @endif
            <form action="{{ route('search') }}" method="GET" class="absolute left-1/2 -translate-x-1/2 w-full max-w-md">
                <input type="text" name="q" placeholder="Search..." class="w-full rounded-md border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ request('q') }}">
            </form>
            <div class="ml-auto flex items-center gap-3">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-500 hover:text-gray-700 relative">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @if(($unreadCount ?? 0) > 0)<span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">{{ $unreadCount }}</span>@endif
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg border z-50">
                        <div class="p-3 border-b text-sm font-semibold flex justify-between">
                            <span>Notifications</span>
                            @if(($unreadCount ?? 0) > 0)<form method="POST" action="{{ route('notifications.read-all') }}">@csrf<button class="text-xs text-indigo-600">Mark all read</button></form>@endif
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse($unreadNotifications ?? [] as $n)
                            <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 border-b hover:bg-indigo-50 text-sm block">
                                    <p class="text-gray-800">{{ $n->data['message'] ?? 'Notification' }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $n->created_at->diffForHumans() }}</p>
                                </button>
                            </form>
                            @empty
                            <p class="px-4 py-3 text-sm text-gray-500">No new notifications</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 text-sm text-gray-700">
                        <div class="h-8 w-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-semibold text-xs">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                        <span class="hidden md:block max-w-28 truncate">{{ auth()->user()->name }}</span>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button></form>
                    </div>
                </div>
            </div>
        </header>

        @if(session('success') || session('error') || session('warning'))
        <div class="px-6 pt-3">
            @if(session('success'))<div class="mb-2 px-4 py-3 bg-green-50 border border-green-300 text-green-700 rounded-md text-sm flex justify-between items-center" x-data x-init="setTimeout(()=>$el.remove(),5000)"><span>{{ session('success') }}</span><button @click="$el.remove()" class="ml-4 font-bold">&times;</button></div>@endif
            @if(session('error'))<div class="mb-2 px-4 py-3 bg-red-50 border border-red-300 text-red-700 rounded-md text-sm flex justify-between" x-data><span>{{ session('error') }}</span><button @click="$el.remove()" class="ml-4 font-bold">&times;</button></div>@endif
            @if(session('warning'))<div class="mb-2 px-4 py-3 bg-yellow-50 border border-yellow-300 text-yellow-700 rounded-md text-sm">{{ session('warning') }}</div>@endif
        </div>
        @endif

        <main class="flex-1 overflow-y-auto px-6 pb-6">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
