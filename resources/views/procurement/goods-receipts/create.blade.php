<x-app-layout>
    <x-slot name="title">New Goods Receipt</x-slot>
    <div class="py-6 max-w-4xl">
        <form method="POST" action="{{ route('goods-receipts.store') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Order *</label>
                        <select name="purchase_order_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select PO</option>
                            @foreach($purchaseOrders as $po)
                            <option value="{{ $po->id }}" @selected(old('purchase_order_id', request('po_id')) == $po->id)>{{ $po->po_number }} — {{ optional($po->vendor)->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('purchase_order_id')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Received At *</label>
                        <input type="datetime-local" name="received_at" value="{{ old('received_at', now()->format('Y-m-d\TH:i')) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('goods-receipts.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Receipt</button>
            </div>
        </form>
    </div>
</x-app-layout>
