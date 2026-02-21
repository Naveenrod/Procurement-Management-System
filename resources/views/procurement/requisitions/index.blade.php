<x-app-layout>
    <x-slot name="title">Purchase Requisitions</x-slot>
    <div class="py-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="border rounded-md px-3 py-1.5 text-sm">
                    <select name="status" class="border rounded-md px-3 py-1.5 text-sm">
                        <option value="">All Statuses</option>
                        @foreach(\App\Enums\RequisitionStatus::cases() as $s)
                        <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
                        @endforeach
                    </select>
                    <button class="px-3 py-1.5 bg-gray-100 rounded-md text-sm">Filter</button>
                </form>
            </div>
            @can('manage-procurement')
            <a href="{{ route('requisitions.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">+ New Requisition</a>
            @endcan
        </div>

        @if($requisitions->count())
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Number</th>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-left">Department</th>
                        <th class="px-4 py-3 text-left">Priority</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Required</th>
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($requisitions as $req)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $req->requisition_number }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ Str::limit($req->title, 35) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $req->department }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$req->priority" /></td>
                        <td class="px-4 py-3"><x-status-badge :status="$req->status" /></td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($req->required_date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-800">${{ number_format($req->total_amount, 2) }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('requisitions.show', $req) }}" class="text-blue-600 hover:underline text-xs">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $requisitions->withQueryString()->links() }}</div>
        @else
        <x-empty-state title="No requisitions found" description="Create your first purchase requisition to get started." action-url="{{ route('requisitions.create') }}" action-label="New Requisition" />
        @endif
    </div>
</x-app-layout>
