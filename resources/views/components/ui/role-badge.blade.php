@props([
    'role',
    'color' => null,  // override optional
])

@php
    // Standar warna berdasarkan role
    $defaultColors = [
        'admin'    => 'bg-blue-100 text-blue-800',
        'leader'   => 'bg-indigo-100 text-indigo-800',
        'hrd'      => 'bg-yellow-100 text-yellow-800',
        'employee' => 'bg-green-100 text-green-800',
    ];

    // Warna otomatis dari role
    $roleColor = $defaultColors[$role] ?? 'bg-gray-100 text-gray-800';

    // FINAL CLASS → jika color null → pakai $roleColor
    $finalColor = $color ?: $roleColor;

    // Label
    $label = ucfirst($role);
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex px-2 py-1 rounded-full text-xs font-semibold ' . $finalColor
]) }}>
    {{ $label }}
</span>
