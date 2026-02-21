<x-app-layout>
    <x-slot name="title">Barcode Scan</x-slot>
    <div class="py-6 max-w-2xl" x-data="{ result: null, loading: false }">
        <div class="bg-white rounded-lg shadow-sm border p-8 text-center">
            <h2 class="text-xl font-bold text-gray-800 mb-2">Barcode / QR Scanner</h2>
            <p class="text-sm text-gray-500 mb-6">Scan a barcode or enter it manually below</p>
            <form method="POST" action="{{ route('warehouse.scan.process') }}" @submit="loading = true" class="flex gap-3 max-w-sm mx-auto">
                @csrf
                <input type="text" name="barcode" id="barcodeInput" autofocus placeholder="Scan or type barcode..." class="flex-1 border rounded-md px-4 py-3 text-lg font-mono tracking-wider text-center" required>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">Scan</button>
            </form>
        </div>
        @if(session('scan_result'))
        <div class="mt-4 bg-white rounded-lg shadow-sm border p-6">
            <h3 class="font-semibold text-gray-800 mb-3">Scan Result</h3>
            @php $result = session('scan_result'); @endphp
            @if(isset($result['product']))
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Product</span><span class="font-medium">{{ $result['product'] }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">SKU</span><span class="font-mono">{{ $result['sku'] }}</span></div>
                @if(isset($result['stock']))<div class="flex justify-between"><span class="text-gray-500">Stock</span><span class="font-medium">{{ $result['stock'] }}</span></div>@endif
            </div>
            @else
            <p class="text-red-600 text-sm">{{ $result['message'] ?? 'No matching item found.' }}</p>
            @endif
        </div>
        @endif
    </div>
</x-app-layout>
