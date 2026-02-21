@props(['status', 'label' => null])
@php
    $text = $label ?? (is_object($status) ? $status->label() : ucfirst(str_replace('_', ' ', $status)));
    $color = is_object($status) ? $status->color() : match($status) {
        'active', 'approved', 'complete', 'completed', 'paid', 'matched', 'available' => 'green',
        'pending', 'draft', 'invited', 'scheduled' => 'yellow',
        'rejected', 'cancelled', 'suspended', 'terminated', 'overdue' => 'red',
        'in_progress', 'sent', 'partial', 'processing' => 'blue',
        'expired', 'awarded', 'closed' => 'gray',
        default => 'gray',
    };
    $classes = match($color) {
        'green' => 'bg-green-100 text-green-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'red' => 'bg-red-100 text-red-800',
        'blue' => 'bg-blue-100 text-blue-800',
        'purple' => 'bg-purple-100 text-purple-800',
        'orange' => 'bg-orange-100 text-orange-800',
        default => 'bg-gray-100 text-gray-700',
    };
@endphp
<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $classes }}">{{ $text }}</span>
