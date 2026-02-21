<x-app-layout>
    <x-slot name="title">Invoices</x-slot>
    <div class="py-6">
        <div class="flex justify-between items-center mb-4">
            <form method="GET" class="flex gap-2">
                <select name="status" class="border rounded-md px-3 py-1.5 text-sm">
                    <option value="">All Statuses</option>
                    @foreach(\App\Enums\InvoiceStatus::cases() as $s)
                    <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
                <button class="px-3 py-1.5 bg-gray-100 rounded-md text-sm">Filter</button>
            </form>
            <a href="{{ route('invoices.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New Invoice</a>
        </div>
        @if($invoices->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Invoice #</th>
                        <th class="px-4 py-3 text-left">Vendor</th>
                        <th class="px-4 py-3 text-left">PO #</th>
                        <th class="px-4 py-3 text-left">Invoice Date</th>
                        <th class="px-4 py-3 text-left">Due Date</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Match</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($invoices as $invoice)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $invoice->invoice_number }}</td>
                        <td class="px-4 py-3">{{ optional($invoice->vendor)->name }}</td>
                        <td class="px-4 py-3"><a href="{{ route('purchase-orders.show', $invoice->purchaseOrder) }}" class="text-blue-600 hover:underline text-xs">{{ optional($invoice->purchaseOrder)->po_number }}</a></td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($invoice->invoice_date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($invoice->due_date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right font-medium">${{ number_format($invoice->total_amount, 2) }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$invoice->status" /></td>
                        <td class="px-4 py-3"><x-status-badge :status="$invoice->three_way_match_status" /></td>
                        <td class="px-4 py-3"><a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $invoices->links() }}</div>
        @else
        <x-empty-state title="No invoices found" action-url="{{ route('invoices.create') }}" action-label="New Invoice" />
        @endif
    </div>
</x-app-layout>
