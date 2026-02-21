@props(['title' => 'No records found', 'description' => null, 'actionUrl' => null, 'actionLabel' => 'Add New'])
<div class="text-center py-16">
    <div class="text-5xl mb-4">📋</div>
    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $title }}</h3>
    @if($description)<p class="text-sm text-gray-500 mb-4">{{ $description }}</p>@endif
    @if($actionUrl)
    <a href="{{ $actionUrl }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
        + {{ $actionLabel }}
    </a>
    @endif
</div>
