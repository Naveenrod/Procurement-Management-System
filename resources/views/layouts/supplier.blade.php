<!DOCTYPE html>
<html lang="en"
    x-data="{ dark: localStorage.getItem('theme') === 'dark' }"
    x-bind:class="{ 'dark': dark }"
    x-init="$watch('dark', val => localStorage.setItem('theme', val ? 'dark' : 'light'))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Supplier Portal - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased">
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
        <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6">
            <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">@yield('title', 'Supplier Portal')</h1>
            {{-- Dark mode toggle --}}
            <button @click="dark = !dark"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                :title="dark ? 'Switch to light mode' : 'Switch to dark mode'">
                <svg x-show="!dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg x-show="dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>
        </header>
        @if(session('success') || session('error'))
        <div class="px-6 pt-3">
            @if(session('success'))<div class="mb-2 px-4 py-3 bg-green-50 dark:bg-green-900/30 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 rounded-md text-sm">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="mb-2 px-4 py-3 bg-red-50 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 rounded-md text-sm">{{ session('error') }}</div>@endif
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
