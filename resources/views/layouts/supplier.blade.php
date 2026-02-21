<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Supplier Portal - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">
<div class="flex h-screen overflow-hidden">
    <aside class="w-64 bg-blue-900 text-white flex flex-col flex-shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-blue-700">
            <span class="text-xl font-bold">Supplier Portal</span>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5 text-sm">
            <a href="{{ route('supplier.dashboard') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('supplier.dashboard') ? 'bg-blue-600 text-white' : 'text-blue-200 hover:bg-blue-700' }}">Dashboard</a>
            <a href="{{ route('supplier.purchase-orders.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('supplier.purchase-orders.*') ? 'bg-blue-600 text-white' : 'text-blue-200 hover:bg-blue-700' }}">Purchase Orders</a>
            <a href="{{ route('supplier.invoices.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('supplier.invoices.*') ? 'bg-blue-600 text-white' : 'text-blue-200 hover:bg-blue-700' }}">Invoices</a>
            <a href="{{ route('supplier.rfqs.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('supplier.rfqs.*') ? 'bg-blue-600 text-white' : 'text-blue-200 hover:bg-blue-700' }}">RFQs</a>
            <a href="{{ route('supplier.performance') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('supplier.performance') ? 'bg-blue-600 text-white' : 'text-blue-200 hover:bg-blue-700' }}">Performance</a>
            <a href="{{ route('supplier.profile.edit') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('supplier.profile.*') ? 'bg-blue-600 text-white' : 'text-blue-200 hover:bg-blue-700' }}">My Profile</a>
        </nav>
        <div class="p-3 border-t border-blue-700">
            <p class="text-xs text-blue-300 truncate">{{ auth()->user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">@csrf<button type="submit" class="text-xs text-blue-400 hover:text-white">Logout</button></form>
        </div>
    </aside>
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b flex items-center px-6">
            <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Supplier Portal')</h1>
        </header>
        @if(session('success') || session('error'))
        <div class="px-6 pt-3">
            @if(session('success'))<div class="mb-2 px-4 py-3 bg-green-50 border border-green-300 text-green-700 rounded-md text-sm">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="mb-2 px-4 py-3 bg-red-50 border border-red-300 text-red-700 rounded-md text-sm">{{ session('error') }}</div>@endif
        </div>
        @endif
        <main class="flex-1 overflow-y-auto px-6 pb-6">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>
</div>
</body>
</html>
