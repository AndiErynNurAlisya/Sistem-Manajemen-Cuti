{{-- resources/views/components/ui/leave-type-badge.blade.php --}}
@props(['type'])

@php
    $typeColors = [
        // Annual = Army Gelap
        'annual' => 'bg-[#334124] text-white',

        // Sick = Agak Cream
        'sick' => 'bg-[#b5b89b] text-[#334124]',
    ];
    
    // Default = warna dashboard
    $colorClass = $typeColors[$type->value] ?? 'bg-[#566534] text-white';

    $label = $type->label();
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$colorClass}"]) }}>
    {{ $label }}
</span>
