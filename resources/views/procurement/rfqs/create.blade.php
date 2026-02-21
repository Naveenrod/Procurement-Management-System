<x-app-layout>
    <x-slot name="title">New RFQ</x-slot>
    <div class="py-6 max-w-4xl">
        <form method="POST" action="{{ route('rfqs.store') }}">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Closing Date *</label>
                        <input type="date" name="closing_date" value="{{ old('closing_date') }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Issue Date *</label>
                        <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Linked Requisition</label>
                        <select name="purchase_requisition_id" class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">None</option>
                            @foreach($requisitions ?? [] as $req)
                            <option value="{{ $req->id }}">{{ $req->requisition_number }} — {{ $req->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Invite Vendors</label>
                        <div class="grid grid-cols-3 gap-2 max-h-40 overflow-y-auto border rounded-md p-3">
                            @foreach($vendors as $vendor)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="vendor_ids[]" value="{{ $vendor->id }}" @checked(in_array($vendor->id, old('vendor_ids', [])))>
                                {{ $vendor->name }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('rfqs.index') }}" class="px-4 py-2 border rounded-md text-sm text-gray-700">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Create RFQ</button>
            </div>
        </form>
    </div>
</x-app-layout>
