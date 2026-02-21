@extends('layouts.supplier')
@section('title', 'Supplier Dashboard')
@section('content')
<div class="py-6 space-y-6">
    @php $vendor = auth()->user()->vendor; @endphp
    <div class="bg-blue-900 text-white rounded-lg p-6 mb-4">
        <h2 class="text-xl font-bold">Welcome, {{ optional($vendor)->name ?? auth()->user()->name }}</h2>
        <p class="text-blue-300 text-sm mt-1">Supplier Portal Dashboard</p>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-sm border p-5 text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $openPos ?? 0 }}</p>
            <p class="text-sm text-gray-500 mt-1">Open Purchase Orders</p>
            <a href="{{ route('supplier.purchase-orders.index') }}" class="text-xs text-blue-500 hover:underline mt-2 block">View →</a>
        </div>
        <div class="bg-white rounded-lg shadow-sm border p-5 text-center">
            <p class="text-3xl font-bold text-yellow-600">{{ $pendingInvoices ?? 0 }}</p>
            <p class="text-sm text-gray-500 mt-1">Pending Invoices</p>
            <a href="{{ route('supplier.invoices.index') }}" class="text-xs text-blue-500 hover:underline mt-2 block">View →</a>
        </div>
        <div class="bg-white rounded-lg shadow-sm border p-5 text-center">
            <p class="text-3xl font-bold text-green-600">{{ $performanceScore ?? 'N/A' }}</p>
            <p class="text-sm text-gray-500 mt-1">Performance Score</p>
            <a href="{{ route('supplier.performance') }}" class="text-xs text-blue-500 hover:underline mt-2 block">View →</a>
        </div>
    </div>
</div>
@endsection
