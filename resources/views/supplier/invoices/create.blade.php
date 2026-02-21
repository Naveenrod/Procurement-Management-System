@extends('layouts.supplier')
@section('title', 'Submit Invoice')
@section('content')
<div class="py-6 max-w-2xl">
    <form method="POST" action="{{ route('supplier.invoices.store') }}">
        @csrf
        <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Order *</label>
                    <select name="purchase_order_id" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <option value="">Select PO</option>
                        @foreach($purchaseOrders as $po)
                        <option value="{{ $po->id }}" @selected(old('purchase_order_id') == $po->id)>{{ $po->po_number }} — ${{ number_format($po->total_amount, 2) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Date *</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Due Date *</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount *</label>
                    <input type="number" name="subtotal" value="{{ old('subtotal') }}" step="0.01" required class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-4">
            <a href="{{ route('supplier.invoices.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Submit Invoice</button>
        </div>
    </form>
</div>
@endsection
