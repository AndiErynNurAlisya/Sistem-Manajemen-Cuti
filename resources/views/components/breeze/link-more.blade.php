@props([
    'href' => '#',
    'text' => 'Lihat Semua',
    'color' => 'indigo',
])

@php
    $colors = [
        'indigo' => '!text-indigo-600 hover:!text-indigo-800',
        'black'  => '!text-gray-900 hover:!text-black',
        'gray'   => '!text-gray-600 hover:!text-gray-800',
    ];

    $classColor = $colors[$color] ?? $colors['indigo'];
@endphp

<a href="{{ $href }}"
   {{ $attributes->merge([
       'class' => "text-sm font-medium $classColor"
   ]) }}>
    {{ $text }} →
</a>
