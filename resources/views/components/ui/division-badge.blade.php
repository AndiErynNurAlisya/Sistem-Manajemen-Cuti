@props([
    'name' => '-',   // nama divisi
    'color' => null  // override optional
])

@php
    // warna default divisi
    $default = 'bg-blue-100 text-blue-800';

    // final warna: override > default
    $finalColor = $color ?: $default;

    $label = $name ?: '-';
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex px-2 py-1 rounded-full text-xs font-semibold ' . $finalColor
]) }}>
    {{ $label }}
</span>
