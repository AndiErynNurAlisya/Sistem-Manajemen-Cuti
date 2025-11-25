{{-- resources/views/components/ui/leave-type-badge.blade.php --}}
@props(['type'])

@php
    $typeColors = [
        'annual' => 'bg-blue-100 text-blue-800',
        'sick' => 'bg-red-100 text-red-800',
    ];
    
    $colorClass = $typeColors[$type->value] ?? 'bg-gray-100 text-gray-800';
    $label = $type->label();
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$colorClass}"]) }}>
    {{ $label }}
</span>