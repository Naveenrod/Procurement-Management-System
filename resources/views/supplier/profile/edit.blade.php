@extends('layouts.supplier')
@section('title', 'My Profile')
@section('content')
<div class="py-6 max-w-2xl">
    <form method="POST" action="{{ route('supplier.profile.update') }}">
        @csrf @method('PUT')
        <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                    <input type="text" name="name" value="{{ old('name', optional(auth()->user()->vendor)->name) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', optional(auth()->user()->vendor)->email) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', optional(auth()->user()->vendor)->phone) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="website" value="{{ old('website', optional(auth()->user()->vendor)->website) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('address', optional(auth()->user()->vendor)->address) }}</textarea>
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save Profile</button>
        </div>
    </form>
</div>
@endsection
