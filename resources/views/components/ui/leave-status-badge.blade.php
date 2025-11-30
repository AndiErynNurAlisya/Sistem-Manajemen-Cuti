@props(['status'])

@php
    $statusColors = [
        // Pending = cream
        'pending' => 'bg-[#b5b89b] text-[#334124]',

        // Approved by Leader = army dashboard
        'approved_by_leader' => 'bg-[#566534] text-white',

        // Approved = army gelap (warna utama)
        'approved' => 'bg-[#334124] text-white',

        // Rejected = merah lembut
        'rejected' => 'bg-[#f8d7da] text-[#842029]',

        // Cancelled = abu soft
        'cancelled' => 'bg-gray-200 text-gray-800',
    ];

    $colorClass = $statusColors[$status->value] ?? 'bg-gray-100 text-gray-800';
    $label = $status->label();
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$colorClass}"
]) }}>
    {{ $label }}
</span>
