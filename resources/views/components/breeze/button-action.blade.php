@props(['href' => '#', 'color' => 'default', 'confirm' => null, 'method' => 'GET'])

@php
    // Skema warna custom
    $colorSchemes = [
        'army' => 'text-[#334124] hover:text-[#1f2716]',
        'cream' => 'text-[#b5b89b] hover:text-[#8f947c]',
        'dashboard' => 'text-[#566534] hover:text-[#3f4a24]',
        'danger' => 'text-red-600 hover:text-red-800',
    ];

    // default fallback = dashboard
    $colorClass = $colorSchemes[$color] ?? $colorSchemes['dashboard'];
@endphp

@if($method === 'GET')
    <a href="{{ $href }}"
       {{ $attributes->merge(['class' => "$colorClass transition font-medium"]) }}>
        {{ $slot }}
    </a>
@else
    <form action="{{ $href }}" method="POST"
          {{ $attributes->merge(['class' => 'inline']) }}
          onsubmit="{{ $confirm ? "return confirm('{$confirm}')" : '' }}">
        @csrf
        @method($method)

        <button type="submit"
                class="{{ $colorClass }} transition font-medium">
            {{ $slot }}
        </button>
    </form>
@endif
