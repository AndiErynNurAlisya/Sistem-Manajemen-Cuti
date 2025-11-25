{{-- resources/views/components/ui/leave-status-badge.blade.php --}}
@props(['status'])

@php
    $statusColors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'approved_by_leader' => 'bg-blue-100 text-blue-800',
        'approved' => 'bg-green-100 text-green-800',
        'rejected' => 'bg-red-100 text-red-800',
        'cancelled' => 'bg-gray-100 text-gray-800',
    ];
    
    $colorClass = $statusColors[$status->value] ?? 'bg-gray-100 text-gray-800';
    $label = $status->label();
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$colorClass}"]) }}>
    {{ $label }}
</span>