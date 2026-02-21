<x-app-layout>
    <x-slot name="title">Vendors</x-slot>
    <div class="py-6">
        <div class="flex justify-between items-center mb-4">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, code..." class="border rounded-md px-3 py-1.5 text-sm">
                <select name="status" class="border rounded-md px-3 py-1.5 text-sm min-w-[10rem]">
                    <option value="">All Statuses</option>
                    @foreach(\App\Enums\VendorStatus::cases() as $s)
                    <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
                <button class="px-3 py-1.5 bg-gray-100 rounded-md text-sm">Filter</button>
            </form>
            @can('manage-vendors')
            <a href="{{ route('vendors.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New Vendor</a>
            @endcan
        </div>
        @if($vendors->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Code</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Country</th>
                        <th class="px-4 py-3 text-left">Contact</th>
                        <th class="px-4 py-3 text-left">Payment Terms</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($vendors as $vendor)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $vendor->code }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $vendor->name }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$vendor->status" /></td>
                        <td class="px-4 py-3 text-gray-500">{{ $vendor->country ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $vendor->email ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $vendor->payment_terms ?? '—' }}</td>
                        <td class="px-4 py-3"><a href="{{ route('vendors.show', $vendor) }}" class="text-blue-600 hover:underline text-xs">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $vendors->withQueryString()->links() }}</div>
        @else
        <x-empty-state title="No vendors found" action-url="{{ route('vendors.create') }}" action-label="New Vendor" />
        @endif
    </div>
</x-app-layout>
