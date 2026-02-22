<x-app-layout>
    <x-slot name="title">{{ $vendor->name }}</x-slot>
    <div class="py-6 max-w-5xl space-y-4" x-data="{ tab: 'details' }">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $vendor->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $vendor->code }} · {{ $vendor->country ?? '—' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-status-badge :status="$vendor->status" />
                    @if($vendor->status?->value === 'pending')
                    <form method="POST" action="{{ route('vendors.approve', $vendor) }}">@csrf
                        <button class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">Approve</button>
                    </form>
                    @endif
                    @if($vendor->status?->value === 'pending' || $vendor->status?->value === 'approved')
                    <form method="POST" action="{{ route('vendors.suspend', $vendor) }}">@csrf
                        <button class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">Reject</button>
                    </form>
                    @endif
                    @if($vendor->status?->value === 'suspended')
                    <form method="POST" action="{{ route('vendors.approve', $vendor) }}">@csrf
                        <button class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">Reinstate</button>
                    </form>
                    @endif
                    <a href="{{ route('vendors.edit', $vendor) }}" class="px-3 py-1.5 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border">
            <div class="border-b flex">
                @foreach(['details' => 'Details', 'contacts' => 'Contacts', 'documents' => 'Documents', 'performance' => 'Performance'] as $key => $label)
                <button @click="tab = '{{ $key }}'" :class="tab === '{{ $key }}' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium -mb-px">{{ $label }}</button>
                @endforeach
            </div>

            <div x-show="tab === 'details'" class="p-6">
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div><p class="text-gray-500">Email</p><p class="font-medium">{{ $vendor->email ?? '—' }}</p></div>
                    <div><p class="text-gray-500">Phone</p><p class="font-medium">{{ $vendor->phone ?? '—' }}</p></div>
                    <div><p class="text-gray-500">Website</p>@if($vendor->website)<a href="{{ $vendor->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $vendor->website }}</a>@else<p>—</p>@endif</div>
                    <div><p class="text-gray-500">Payment Terms</p><p class="font-medium">{{ $vendor->payment_terms ?? '—' }}</p></div>
                    <div><p class="text-gray-500">Tax ID</p><p class="font-medium">{{ $vendor->tax_id ?? '—' }}</p></div>
                    <div><p class="text-gray-500">Address</p><p class="font-medium">{{ $vendor->address ?? '—' }}</p></div>
                    @if($vendor->notes)<div class="col-span-3"><p class="text-gray-500">Notes</p><p>{{ $vendor->notes }}</p></div>@endif
                </div>
            </div>

            <div x-show="tab === 'contacts'" class="p-6">
                <form method="POST" action="{{ route('vendors.contacts.store', $vendor) }}" class="grid grid-cols-4 gap-2 mb-4">
                    @csrf
                    <input type="text" name="name" placeholder="Name *" required class="border rounded-md px-2 py-1.5 text-sm">
                    <input type="text" name="position" placeholder="Position" class="border rounded-md px-2 py-1.5 text-sm">
                    <input type="email" name="email" placeholder="Email" class="border rounded-md px-2 py-1.5 text-sm">
                    <button class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md">Add Contact</button>
                </form>
                @if($vendor->contacts->count())
                <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-3 py-2 text-left">Name</th><th class="px-3 py-2 text-left">Position</th><th class="px-3 py-2 text-left">Email</th><th class="px-3 py-2 text-left">Phone</th><th class="px-3 py-2"></th></tr></thead>
                <tbody class="divide-y">
                @foreach($vendor->contacts as $contact)
                <tr><td class="px-3 py-2">{{ $contact->name }}</td><td class="px-3 py-2 text-gray-500">{{ $contact->position ?? '—' }}</td><td class="px-3 py-2 text-gray-500">{{ $contact->email ?? '—' }}</td><td class="px-3 py-2 text-gray-500">{{ $contact->phone ?? '—' }}</td>
                <td class="px-3 py-2"><form method="POST" action="{{ route('vendors.contacts.destroy', [$vendor, $contact]) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-600 text-xs">Delete</button></form></td></tr>
                @endforeach
                </tbody></table>
                @else<p class="text-sm text-gray-500">No contacts yet.</p>@endif
            </div>

            <div x-show="tab === 'documents'" class="p-6">
                <form method="POST" action="{{ route('vendors.documents.store', $vendor) }}" enctype="multipart/form-data" class="flex gap-2 mb-4">
                    @csrf
                    <input type="text" name="name" placeholder="Document name *" required class="border rounded-md px-2 py-1.5 text-sm flex-1">
                    <input type="file" name="file" required class="border rounded-md px-2 py-1.5 text-sm">
                    <button class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md">Upload</button>
                </form>
                @if($vendor->documents->count())
                <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-3 py-2 text-left">Name</th><th class="px-3 py-2 text-left">Type</th><th class="px-3 py-2 text-left">Expires</th><th class="px-3 py-2"></th></tr></thead>
                <tbody class="divide-y">
                @foreach($vendor->documents as $doc)
                <tr><td class="px-3 py-2 font-medium">{{ $doc->name }}</td><td class="px-3 py-2 text-gray-500">{{ $doc->document_type ?? '—' }}</td><td class="px-3 py-2 text-gray-500">{{ optional($doc->expiry_date)->format('M d, Y') ?? '—' }}</td>
                <td class="px-3 py-2 flex gap-2">
                    <a href="{{ route('vendors.documents.download', [$vendor, $doc]) }}" class="text-blue-600 hover:underline text-xs">Download</a>
                    <form method="POST" action="{{ route('vendors.documents.destroy', [$vendor, $doc]) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-600 text-xs">Delete</button></form>
                </td></tr>
                @endforeach
                </tbody></table>
                @else<p class="text-sm text-gray-500">No documents yet.</p>@endif
            </div>

            <div x-show="tab === 'performance'" class="p-6">
                <div class="flex justify-end mb-3">
                    <a href="{{ route('vendors.performance.create', $vendor) }}" class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md">+ Score</a>
                </div>
                @if($vendor->performanceScores->count())
                <table class="w-full text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-3 py-2 text-left">Period</th><th class="px-3 py-2 text-center">Delivery</th><th class="px-3 py-2 text-center">Quality</th><th class="px-3 py-2 text-center">Price</th><th class="px-3 py-2 text-center">Overall</th></tr></thead>
                <tbody class="divide-y">
                @foreach($vendor->performanceScores->sortByDesc('period_start') as $score)
                <tr><td class="px-3 py-2 text-gray-500">{{ optional($score->period_start)->format('M Y') }}</td><td class="px-3 py-2 text-center">{{ $score->delivery_score }}</td><td class="px-3 py-2 text-center">{{ $score->quality_score }}</td><td class="px-3 py-2 text-center">{{ $score->price_score }}</td><td class="px-3 py-2 text-center font-bold">{{ $score->overall_score }}</td></tr>
                @endforeach
                </tbody></table>
                @else<p class="text-sm text-gray-500">No performance scores yet.</p>@endif
            </div>
        </div>
    </div>
</x-app-layout>
