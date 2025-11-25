{{-- resources/views/components/ui/quick-action.blade.php --}}
@props([
    'href',
    'title',
    'subtitle',
    'icon',
    'color' => 'indigo' // indigo, gray, yellow, green, red
])

@php
    $colors = [
        'indigo' => 'border-indigo-200 hover:bg-indigo-50 group-hover:bg-indigo-200 bg-indigo-100 text-indigo-600',
        'gray' => 'border-gray-200 hover:bg-gray-50 group-hover:bg-gray-200 bg-gray-100 text-gray-600',
        'yellow' => 'border-yellow-200 hover:bg-yellow-50 group-hover:bg-yellow-200 bg-yellow-100 text-yellow-600',
        'green' => 'border-green-200 hover:bg-green-50 group-hover:bg-green-200 bg-green-100 text-green-600',
        'red' => 'border-red-200 hover:bg-red-50 group-hover:bg-red-200 bg-red-100 text-red-600',
    ];
    
    $colorClass = explode(' ', $colors[$color] ?? $colors['indigo']);
@endphp

<a href="{{ $href }}" 
   class="flex items-center p-4 border-2 {{ $colorClass[0] }} rounded-lg {{ $colorClass[1] }} transition group">
    <div class="p-3 {{ $colorClass[3] }} rounded-lg {{ $colorClass[2] }} transition">
        <svg class="w-6 h-6 {{ $colorClass[4] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    </div>
    <div class="ml-4">
        <p class="font-semibold text-gray-900">{{ $title }}</p>
        <p class="text-xs text-gray-500">{{ $subtitle }}</p>
    </div>
</a>