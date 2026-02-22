<x-app-layout>
    <x-slot name="title">New Goods Receipt</x-slot>
    <div class="py-6 max-w-4xl">
        @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Step 1: PO selector (reloads page to populate items) --}}
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Order *</label>
            <div class="flex gap-3">
                <select id="po-picker" class="flex-1 border rounded-md px-3 py-2 text-sm"
                        onchange="if(this.value) window.location='{{ route('procurement.goods-receipts.create') }}?po_id='+this.value">
                    <option value="">— Select a PO to load its items —</option>
                    @foreach($purchaseOrders as $po)
                    <option value="{{ $po->id }}" @selected(optional($selectedPo)->id == $po->id)>
                        {{ $po->po_number }} — {{ optional($po->vendor)->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($selectedPo)
        <form method="POST" action="{{ route('procurement.goods-receipts.store') }}">
            @csrf
            <input type="hidden" name="purchase_order_id" value="{{ $selectedPo->id }}">

            {{-- Header fields --}}
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Received At *</label>
                        <input type="datetime-local" name="received_at"
                               value="{{ old('received_at', now()->format('Y-m-d\TH:i')) }}"
                               required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('received_at')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden mb-4">
                <div class="px-5 py-3 border-b bg-gray-50">
                    <p class="text-sm font-medium text-gray-700">
                        Line Items — {{ $selectedPo->po_number }}
                        <span class="text-gray-400 font-normal ml-2">{{ optional($selectedPo->vendor)->name }}</span>
                    </p>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">Product</th>
                            <th class="px-4 py-3 text-right">Ordered Qty</th>
                            <th class="px-4 py-3 text-right">Received</th>
                            <th class="px-4 py-3 text-right">Accepted</th>
                            <th class="px-4 py-3 text-right">Rejected</th>
                            <th class="px-4 py-3 text-left">Rejection Reason</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($selectedPo->items as $i => $poItem)
                        <tr class="hover:bg-gray-50">
                            <input type="hidden" name="items[{{ $i }}][purchase_order_item_id]" value="{{ $poItem->id }}">
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ optional($poItem->product)->name }}
                                <div class="text-xs text-gray-400 font-mono">{{ optional($poItem->product)->sku }}</div>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-500">
                                {{ number_format($poItem->quantity, 0) }} {{ optional($poItem->product)->unit_of_measure }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <input type="number" name="items[{{ $i }}][quantity_received]"
                                       value="{{ old("items.{$i}.quantity_received", $poItem->quantity) }}"
                                       min="0" step="0.01" required
                                       class="border rounded-md px-2 py-1.5 text-sm text-right w-24">
                            </td>
                            <td class="px-4 py-3 text-right">
                                <input type="number" name="items[{{ $i }}][quantity_accepted]"
                                       value="{{ old("items.{$i}.quantity_accepted", $poItem->quantity) }}"
                                       min="0" step="0.01" required
                                       class="border rounded-md px-2 py-1.5 text-sm text-right w-24">
                            </td>
                            <td class="px-4 py-3 text-right">
                                <input type="number" name="items[{{ $i }}][quantity_rejected]"
                                       value="{{ old("items.{$i}.quantity_rejected", 0) }}"
                                       min="0" step="0.01"
                                       class="border rounded-md px-2 py-1.5 text-sm text-right w-24">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" name="items[{{ $i }}][rejection_reason]"
                                       value="{{ old("items.{$i}.rejection_reason") }}"
                                       placeholder="Optional"
                                       class="border rounded-md px-2 py-1.5 text-sm w-full">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('procurement.goods-receipts.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Receipt</button>
            </div>
        </form>

        @else
        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg p-10 text-center text-gray-400 text-sm">
            Select a purchase order above to load its line items.
        </div>
        <div class="flex justify-end mt-4">
            <a href="{{ route('procurement.goods-receipts.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
        </div>
        @endif
    </div>
</x-app-layout>
