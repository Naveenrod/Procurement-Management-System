@props(['title', 'value', 'icon' => null, 'change' => null, 'color' => 'blue', 'href' => null])
@php
    $colors = ['blue' => 'bg-blue-500', 'green' => 'bg-green-500', 'yellow' => 'bg-yellow-500', 'red' => 'bg-red-500', 'purple' => 'bg-purple-500', 'orange' => 'bg-orange-500'];
    $bg = $colors[$color] ?? 'bg-blue-500';
@endphp
<div class="bg-white rounded-lg shadow-sm border p-5 flex items-center gap-4">
    @if($icon)
    <div class="flex-shrink-0 w-12 h-12 {{ $bg }} rounded-lg flex items-center justify-center text-white text-xl">{{ $icon }}</div>
    @endif
    <div class="flex-1 min-w-0">
        <p class="text-sm text-gray-500 truncate">{{ $title }}</p>
        <p class="text-2xl font-bold text-gray-800">{{ $value }}</p>
        @if($change !== null)
        <p class="text-xs {{ $change >= 0 ? 'text-green-600' : 'text-red-600' }} mt-0.5">
            {{ $change >= 0 ? '↑' : '↓' }} {{ abs($change) }}%
        </p>
        @endif
    </div>
    @if($href)
    <a href="{{ $href }}" class="flex-shrink-0 text-blue-500 hover:text-blue-700 text-sm">View →</a>
    @endif
</div>
