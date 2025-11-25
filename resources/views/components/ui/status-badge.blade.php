@props([
    'active' => false,   // boolean
    'color' => null      // optional override
])

@php
    $default = $active
        ? 'bg-green-100 text-green-800'
        : 'bg-red-100 text-red-800';

    // override jika color tidak null
    $finalColor = $color ?: $default;

    $label = $active ? 'Aktif' : 'Nonaktif';
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex px-2 py-1 rounded-full text-xs font-semibold ' . $finalColor
]) }}>
    {{ $label }}
</span>
