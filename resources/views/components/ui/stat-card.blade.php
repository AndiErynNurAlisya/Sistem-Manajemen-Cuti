{{-- resources/views/components/ui/stat-card.blade.php --}}
@props([
    'title',
    'value',
    'subtitle' => null,
    'icon',
    'color' => 'indigo', // indigo, red, blue, yellow, green
    'progress' => null, // array: ['current' => 10, 'total' => 12]
])

@php
    $colors = [
        'indigo' => [
            'border' => 'border-indigo-500',
            'bg' => 'bg-indigo-100',
            'text' => 'text-indigo-600',
            'progress' => 'bg-indigo-600'
        ],
        'red' => [
            'border' => 'border-red-500',
            'bg' => 'bg-red-100',
            'text' => 'text-red-600',
            'progress' => 'bg-red-600'
        ],
        'blue' => [
            'border' => 'border-blue-500',
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-600',
            'progress' => 'bg-blue-600'
        ],
        'yellow' => [
            'border' => 'border-yellow-500',
            'bg' => 'bg-yellow-100',
            'text' => 'text-yellow-600',
            'progress' => 'bg-yellow-600'
        ],
        'green' => [
            'border' => 'border-green-500',
            'bg' => 'bg-green-100',
            'text' => 'text-green-600',
            'progress' => 'bg-green-600'
        ],
        'army' => [
            'border' => 'border-[#566534] ',
            'bg' => 'bg-[#b5b89b] ',
            'text' => 'text-[#334124] ',
            'progress' => 'bg-[#334124] '
        ],
        'white' => [
            'border' => 'border-white ',
            'bg' => 'bg-[#b5b89b] ',
            'text' => 'text-black ',
            'progress' => 'bg-gray-900 '
        ],
        'second' => [
            'border' => 'border-[#718355] ',
            'bg' => 'bg-gradient-to-b from-[#cfbb99] to-[#e5d7c4] shadow-md ',
            'text' => 'text-[#444c32] ',
            'progress' => 'bg-[#444c32] '
        ]
    ];
    
    $colorClass = $colors[$color] ?? $colors['indigo'];
@endphp

<div class="bg-white rounded-lg shadow-xl p-6 border-l-4 {{ $colorClass['border'] }}">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600 whitespace-nowrap">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $value }}</p>
            @if($subtitle)
                <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="p-3 mt-2 {{ $colorClass['bg'] }} rounded-full -ml-8 ">
            <svg class="w-8 h-8 {{ $colorClass['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        </div>
    </div>
    
    @if($progress)
        <div class="mt-4">
            <div class="w-full bg-gray-200 rounded-full h-2">
                @php
                    $percentage = $progress['total'] > 0 
                        ? round(($progress['current'] / $progress['total']) * 100) 
                        : 0;
                @endphp
                <div class="{{ $colorClass['progress'] }} h-2 rounded-full transition-all" 
                     style="width: {{ $percentage }}%">
                </div>
            </div>
        </div>
    @endif
</div>