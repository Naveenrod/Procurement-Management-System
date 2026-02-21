<x-app-layout>
    <x-slot name="title">Edit Vendor</x-slot>
    <div class="py-6 max-w-3xl">
        <form method="POST" action="{{ route('vendors.update', $vendor) }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vendor Name *</label>
                        <input type="text" name="name" value="{{ old('name', $vendor->name) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person *</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $vendor->contact_person) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $vendor->email) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $vendor->phone) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" name="country" value="{{ old('country', $vendor->country) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Terms</label>
                        <select name="payment_terms" class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select</option>
                            @foreach(\App\Enums\PaymentTerms::cases() as $pt)
                            <option value="{{ $pt->value }}" @selected(old('payment_terms', $vendor->payment_terms) === $pt->value)>{{ $pt->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="address" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('address', $vendor->address) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('vendors.show', $vendor) }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save Changes</button>
            </div>
        </form>
    </div>
</x-app-layout>
