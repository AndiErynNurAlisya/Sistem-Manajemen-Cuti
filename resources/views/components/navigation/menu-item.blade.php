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

    $isActive = $active;

    if (!$isActive) {
        if ($href !== '#' && !str_starts_with($href, 'javascript:')) {
            if (filter_var($href, FILTER_VALIDATE_URL) || str_starts_with($href, '/') || str_starts_with($href, 'http')) {
                $path = parse_url($href, PHP_URL_PATH);
                $currentPath = '/' . request()->path();
                // ✅ FIX: Hanya match exact path, bukan starts_with
                $isActive = $currentPath === $path;
            } elseif (Route::has($href)) {
                $url = route($href);
                // ✅ FIX: Hanya match exact route name, bukan starts_with
                $isActive = request()->route()->getName() === $href;
            }
        }
    }

    $activeClass = $isActive
        ? 'bg-[#b5b89b] text-[#334124] relative sidebar-active-bubble'
        : 'text-gray-700 hover:translate-x-2 hover:bg-gray-100';

    $badgeColors = [
        'red' => 'bg-red-500',
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'yellow' => 'bg-yellow-500',
    ];
    $badgeClass = $badgeColors[$badgeColor] ?? $badgeColors['red'];
@endphp

<a href="{{ $url }}"
   class="flex items-center px-4 py-3 text-sm font-medium rounded-r-full transition-all duration-300 {{ $activeClass }}"
   x-data
   :class="{ 'justify-center px-2': $store.sidebar.collapsed }"
   :title="$store.sidebar.collapsed ? '{{ strip_tags($slot) }}' : ''">


    @if($icon)
        <svg class="w-5 h-5 flex-shrink-0 transition-all duration-300" 
             :class="{ 'mr-0': $store.sidebar.collapsed, 'mr-3': !$store.sidebar.collapsed }"
             fill="none" 
             stroke="currentColor" 
             viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    @endif

    <span class="flex-1 whitespace-nowrap overflow-hidden transition-all duration-300"
          :class="{ 'opacity-0 w-0': $store.sidebar.collapsed, 'opacity-100': !$store.sidebar.collapsed }">
        {{ $slot }}
    </span>

    {{-- BADGE - Hidden when collapsed --}}
    @if($badge)
        <span class="{{ $badgeClass }} text-white text-xs font-bold px-2 py-1 rounded-full ml-auto transition-all duration-300"
              x-show="!$store.sidebar.collapsed"
              x-transition>
            {{ $badge }}
        </span>
    @endif
</a>

<style>
    .sidebar-active-bubble {
        border-radius: 9999px 0 0 9999px; /* bubble kiri–kanan */
        transform: translateX(6px);
        box-shadow: 3px 0 8px rgba(0,0,0,0.15);
    }
    
    /* Adjust bubble when collapsed */
    [x-cloak] { display: none !important; }
</style>