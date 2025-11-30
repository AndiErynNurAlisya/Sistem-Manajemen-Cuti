@props([
    'title', 
    'value', 
    'subtitle', 
    'color' => 'green', // Default ke skema warna hijau
    'icon' => null
])

@php
    // --- SKEMA WARNA ---
    $schemes = [
        'green' => [
            'bg_class' => 'bg-gradient-to-b from-[#566534] to-[#718355]',
            'text_class' => 'text-white',
            'icon_bg_class' => 'bg-white bg-opacity-20'
        ],
        'cream' => [
            'bg_class' => 'bg-gradient-to-b from-[#cfbb99] to-[#e5d7c4]',
            'text_class' => 'text-gray-800', // Ubah teks menjadi gelap agar terbaca
            'icon_bg_class' => 'bg-black bg-opacity-10'
        ],
    ];

    // Ambil skema yang dipilih, default ke 'green' jika tidak valid
    $scheme = $schemes[$color] ?? $schemes['green'];
    
    // Icon default (Kalender)
    $defaultIcon = '
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    ';
@endphp

{{-- Gabungkan class latar belakang dan teks dari skema yang dipilih --}}
<div class="p-6 rounded-xl shadow-lg {{ $scheme['text_class'] }} {{ $scheme['bg_class'] }}">
    
    <div class="flex items-start justify-between">
        
        {{-- Area Judul dan Nilai --}}
        <div>
            {{-- Judul --}}
            <div class="text-sm font-light leading-snug">
                <div class="opacity-90">{{ $title }}</div> {{-- MENGGUNAKAN $title --}}
            </div>

            {{-- Nilai Angka --}}
            <div class="text-4xl font-bold mt-2">
                {{ $value }}
            </div>
            
            {{-- Subtitle --}}
            <div class="text-sm opacity-70 mt-1">
                {{ $subtitle }}
            </div>
        </div>

        {{-- Ikon (Lingkaran Besar di Kanan Atas) --}}
        <div class="p-3 mt-3 rounded-full flex-shrink-0 {{ $scheme['icon_bg_class'] }}">
            
            {{-- MENGGUNAKAN ICON YANG DITERUSKAN ATAU ICON DEFAULT --}}
            <div class="w-8 h-8 justify-center items-center flex">
                {!! $icon ?? $defaultIcon !!}
            </div>
        </div>
    </div>
    
</div>