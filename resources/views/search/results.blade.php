<x-app-layout>
    <x-slot name="title">Search Results</x-slot>
    <div class="py-6 space-y-6">
        <p class="text-sm text-gray-500">Results for "<strong>{{ request('q') }}</strong>"</p>

        @if(isset($products) && $products->count())
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Products ({{ $products->count() }})</h3></div>
            <div class="divide-y">
                @foreach($products as $p)
                <div class="px-5 py-3 flex justify-between items-center">
                    <div><p class="font-medium text-sm text-gray-800">{{ $p->name }}</p><p class="text-xs text-gray-500">{{ $p->sku }}</p></div>
                    <span class="text-sm text-gray-500">${{ number_format($p->unit_price ?? 0, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($vendors) && $vendors->count())
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Vendors ({{ $vendors->count() }})</h3></div>
            <div class="divide-y">
                @foreach($vendors as $v)
                <div class="px-5 py-3 flex justify-between items-center">
                    <div><p class="font-medium text-sm text-gray-800">{{ $v->name }}</p><p class="text-xs text-gray-500">{{ $v->vendor_code }}</p></div>
                    <a href="{{ route('vendors.show', $v) }}" class="text-blue-600 text-xs hover:underline">View</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($purchaseOrders) && $purchaseOrders->count())
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-5 py-4 border-b"><h3 class="font-semibold text-gray-800">Purchase Orders ({{ $purchaseOrders->count() }})</h3></div>
            <div class="divide-y">
                @foreach($purchaseOrders as $po)
                <div class="px-5 py-3 flex justify-between items-center">
                    <div><p class="font-medium text-sm font-mono">{{ $po->po_number }}</p><p class="text-xs text-gray-500">{{ optional($po->vendor)->name }}</p></div>
                    <a href="{{ route('purchase-orders.show', $po) }}" class="text-blue-600 text-xs hover:underline">View</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if((!isset($products) || !$products->count()) && (!isset($vendors) || !$vendors->count()) && (!isset($purchaseOrders) || !$purchaseOrders->count()))
        <x-empty-state title="No results found" description="Try a different search term." />
        @endif
    </div>
</x-app-layout>
