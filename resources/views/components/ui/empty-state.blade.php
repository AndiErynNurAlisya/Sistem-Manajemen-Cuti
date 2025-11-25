{{-- resources/views/components/ui/empty-state.blade.php --}}
@props([
    'icon',
    'title',
    'description',
    'actionUrl' => null,
    'actionText' => null,
])

<div class="p-12 text-center">
    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $icon !!}
    </svg>
    <p class="text-gray-500 font-medium">{{ $title }}</p>
    <p class="text-sm text-gray-400 mt-1">{{ $description }}</p>
    
    @if($actionUrl && $actionText)
        <a href="{{ $actionUrl }}" 
           class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
            {{ $actionText }}
        </a>
    @endif
</div>