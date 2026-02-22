<x-app-layout>
    <x-slot name="title">Edit Requisition</x-slot>
    <div class="py-6 max-w-4xl">
        <form method="POST" action="{{ route('procurement.requisitions.update', $requisition) }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="title" value="{{ old('title', $requisition->title) }}" required class="w-full border rounded-md px-3 py-2 text-sm">
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                        <select name="department" required class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Select Department</option>
                            @foreach(config('departments') as $dept)
                            <option value="{{ $dept }}" @selected(old('department', $requisition->department) === $dept)>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Required Date</label>
                        <input type="date" name="required_date" value="{{ old('required_date', optional($requisition->required_date)->format('Y-m-d')) }}" class="w-full border rounded-md px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select name="priority" class="w-full border rounded-md px-3 py-2 text-sm">
                            @foreach(\App\Enums\Priority::cases() as $p)
                            <option value="{{ $p->value }}" @selected(old('priority', $requisition->priority) === $p->value)>{{ $p->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('description', $requisition->description) }}</textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('procurement.requisitions.show', $requisition) }}" class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Save Changes</button>
            </div>
        </form>
    </div>
</x-app-layout>
