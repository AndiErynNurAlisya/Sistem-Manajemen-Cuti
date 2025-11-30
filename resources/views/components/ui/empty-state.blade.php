{{-- resources/views/components/ui/empty-state.blade.php --}}
@props([
    'icon' => null,
    'title',
    'description' => null,
    'actionUrl' => null,
    'actionText' => null,
])

<div class="p-12 text-center">
    {{-- Icon --}}
    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        @if($icon)
            {!! $icon !!}
        @else
            {{-- Default Icon (Inbox) --}}
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        @endif
    </svg>
    
    {{-- Title --}}
    <p class="text-gray-500 font-medium">{{ $title }}</p>
    
    {{-- Description --}}
    @if($description)
        <p class="text-sm text-gray-400 mt-1">{{ $description }}</p>
    @endif
    
    {{-- Action Button (optional) --}}
    @if($actionUrl && $actionText)
        <a href="{{ $actionUrl }}" 
           class="inline-block mt-4 px-4 py-2 bg-[#334124]  text-white text-sm font-medium rounded-lg hover:bg-[#334124]  transition">
            {{ $actionText }}
        </a>
    @endif
</div>