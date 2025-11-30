@props([
    'role',
    'color' => null,  // override optional
])

@php
    // Standar warna berdasarkan role
    $defaultColors = [
    // Admin: Menggunakan warna Army Gelap (Paling tegas untuk otoritas tertinggi)
    'admin'    => 'bg-[#334124] text-white', 
    
    // Leader (Ketua Divisi): Menggunakan warna Dashboard (Hijau/Olive sedang)
    'leader'   => 'bg-[#566534] text-white', 
    
    // HRD: Menggunakan warna Cream/Krem (Menarik perhatian, tapi bukan yang tertinggi)
    'hrd'      => 'bg-[#b5b89b] text-[#334124]', 
    
    // Employee (Karyawan): Menggunakan warna yang lebih terang dan netral
    'employee' => 'bg-gray-200 text-gray-800', 
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
