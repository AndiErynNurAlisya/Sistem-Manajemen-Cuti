{{-- resources/views/components/layout/sidebar.blade.php --}}

@props(['user' => null])

@php
    $user = $user ?? auth()->user();
    // Convert Enum to string if needed
    $role = $user->role ?? 'employee';
    $roleString = is_string($role) ? $role : $role->value;
@endphp

<!-- Sidebar for Mobile (Overlay) -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false"
     class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
</div>

<!-- Sidebar Container -->
<div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0"
     :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
    
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 bg-indigo-600">
        <h1 class="text-xl font-bold text-white">Sistem Cuti</h1>
        <button @click="sidebarOpen = false" 
                class="lg:hidden text-white hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- User Info -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                    <span class="text-indigo-600 font-semibold text-sm">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                    </span>
                </div>
            </div>
            <div class="ml-3 flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                    {{ $user->name ?? 'User' }}
                </p>
                <p class="text-xs text-gray-500 capitalize">
                    {{ ucfirst($roleString) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @if($roleString === 'admin')
            <x-navigation.menu-admin />
        @elseif($roleString === 'employee')
            <x-navigation.menu-employee />
        @elseif($roleString === 'hrd')
            <x-navigation.menu-hrd />
        @elseif($roleString === 'leader')
            <x-navigation.menu-leader />
        @endif
    </nav>

    <!-- Sidebar Footer -->
    <div class="border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="flex items-center w-full px-6 py-4 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>