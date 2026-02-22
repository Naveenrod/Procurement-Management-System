<x-app-layout>
    <x-slot name="title">New Purchase Requisition</x-slot>
    <div class="py-6 max-w-4xl">
        <form method="POST" action="{{ route('procurement.requisitions.store') }}" x-data="{ items: [{ product_id: '', quantity: 1, estimated_unit_price: 0, specifications: '' }] }">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                        <select name="department" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select Department</option>
                            @foreach(config('departments') as $dept)
                            <option value="{{ $dept }}" @selected(old('department') === $dept)>{{ $dept }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('department')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Required Date</label>
                        <input type="date" name="required_date" value="{{ old('required_date') }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select name="priority" class="w-full border rounded-md px-3 py-2 text-sm">
                            @foreach(\App\Enums\Priority::cases() as $p)
                            <option value="{{ $p->value }}" @selected(old('priority', 'medium') === $p->value)>{{ $p->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6 mt-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">Line Items</h3>
                    <button type="button" @click="items.push({ product_id: '', quantity: 1, estimated_unit_price: 0, specifications: '' })" class="text-sm text-blue-600 hover:underline">+ Add Item</button>
                </div>
                <div class="grid grid-cols-12 gap-2 mb-1 text-xs font-medium text-gray-500 uppercase px-1">
                    <div class="col-span-4">Product</div>
                    <div class="col-span-2">Quantity</div>
                    <div class="col-span-2">Unit Price</div>
                    <div class="col-span-3">Specifications</div>
                    <div class="col-span-1"></div>
                </div>
                <template x-for="(item, index) in items" :key="index">
                    <div class="grid grid-cols-12 gap-2 mb-3 items-start">
                        <div class="col-span-4">
                            <select :name="'items['+index+'][product_id]'" class="w-full border rounded-md px-2 py-2 text-sm">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" min="0.01" step="0.01" placeholder="Qty" class="w-full border rounded-md px-2 py-2 text-sm">
                        </div>
                        <div class="col-span-2">
                            <input type="number" :name="'items['+index+'][estimated_unit_price]'" x-model="item.estimated_unit_price" step="0.01" placeholder="Unit Price" class="w-full border rounded-md px-2 py-2 text-sm">
                        </div>
                        <div class="col-span-3">
                            <input type="text" :name="'items['+index+'][specifications]'" x-model="item.specifications" placeholder="Specs (optional)" class="w-full border rounded-md px-2 py-2 text-sm">
                        </div>
                        <div class="col-span-1">
                            <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1" class="text-red-400 hover:text-red-600 mt-1.5">✕</button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('procurement.requisitions.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create Requisition</button>
            </div>
        </form>
    </div>
</x-app-layout>
