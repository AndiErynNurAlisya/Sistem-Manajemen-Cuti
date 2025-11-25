{{-- resources/views/components/navigation/menu-item.blade.php --}}

@props([
    'href',
    'active' => false,
    'icon' => null,
    'badge' => null,
    'badgeColor' => 'red'
])

@php
    // Check if href is already a full URL or route name
    $url = $href;
    
    // Determine if this is the active menu item
    $isActive = $active;
    
    if (!$isActive) {
        // Handle special cases: #, javascript:void(0), etc
        if ($href === '#' || str_starts_with($href, 'javascript:')) {
            $isActive = false;
        }
        // If href is a full URL, parse it
        elseif (filter_var($href, FILTER_VALIDATE_URL) || str_starts_with($href, '/') || str_starts_with($href, 'http')) {
            // Extract path from URL
            $path = parse_url($href, PHP_URL_PATH);
            $currentPath = '/' . request()->path();
            
            // Check if current path matches or starts with href path
            $isActive = $currentPath === $path || str_starts_with($currentPath, rtrim($path, '/'));
        } else {
            // Treat as route name - only if route exists
            if (Route::has($href)) {
                $url = route($href);
                $isActive = str_starts_with(request()->route()->getName() ?? '', $href);
            } else {
                // If route doesn't exist, use href as is
                $url = $href;
                $isActive = false;
            }
        }
    }
    
    $activeClass = $isActive ? 'bg-indigo-50 text-indigo-600 border-l-4 border-indigo-600' : 'text-gray-700 hover:bg-gray-50 border-l-4 border-transparent';
    
    $badgeColors = [
        'red' => 'bg-red-500',
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'yellow' => 'bg-yellow-500',
    ];
    $badgeClass = $badgeColors[$badgeColor] ?? $badgeColors['red'];
@endphp

<a href="{{ $url }}" 
   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition {{ $activeClass }}">
    @if($icon)
        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    @endif
    
    <span class="flex-1">{{ $slot }}</span>
    
    @if($badge)
        <span class="{{ $badgeClass }} text-white text-xs font-bold px-2 py-1 rounded-full ml-auto">
            {{ $badge }}
        </span>
    @endif
</a>