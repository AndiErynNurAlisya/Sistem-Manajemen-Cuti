@php
    $user = $user ?? auth()->user();
    $role = $user->role ?? 'employee';
    $roleString = is_string($role) ? $role : $role->value;
@endphp

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

<div class="fixed inset-y-0 left-0 z-50 bg-gray-100 shadow-lg transform transition-all duration-300 ease-in-out lg:translate-x-0"
     :class="{ 
         '-translate-x-full': !sidebarOpen, 
         'translate-x-0': sidebarOpen,
         'w-64': !$store.sidebar.collapsed,
         'w-20': $store.sidebar.collapsed
     }">
    
    <div class="flex items-center h-16 px-6 bg-grey-100"
         :class="{ 'justify-center px-4': $store.sidebar.collapsed, 'justify-between': !$store.sidebar.collapsed }">
        <div class="flex items-center space-x-3 overflow-hidden">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="Logo" 
                 class="w-10 h-10 rounded-lg object-cover flex-shrink-0">

            <h1 class="text-xl font-bold text-[#566534] whitespace-nowrap transition-all duration-300"
                x-show="!$store.sidebar.collapsed"
                x-transition>
                Leave System
            </h1>
        </div>

        <button @click="sidebarOpen = false" 
                x-show="!$store.sidebar.collapsed"
                class="lg:hidden text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div class="hidden lg:block absolute -right-3 top-20 z-10">
        <button @click="$store.sidebar.toggle()"
                class="w-6 h-6 bg-[#566534] hover:bg-[#334124] text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
            <svg class="w-4 h-4 transition-transform duration-300" 
                 :class="{ 'rotate-180': $store.sidebar.collapsed }"
                 fill="none" 
                 stroke="currentColor" 
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
    </div>

    <div class="px-6 py-4 border-b border-gray-200"
         :class="{ 'px-4': $store.sidebar.collapsed }">
        <div class="flex items-center" :class="{ 'justify-center': $store.sidebar.collapsed }">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-[#334124] flex items-center justify-center">
                    <span class="text-[#b5b89b] font-semibold text-sm">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                    </span>
                </div>
            </div>
            <div class="ml-3 flex-1 min-w-0 transition-all duration-300"
                 x-show="!$store.sidebar.collapsed"
                 x-transition>
                <p class="text-sm font-medium text-gray-900 truncate">
                    {{ $user->name ?? 'User' }}
                </p>
                <p class="text-xs text-gray-500 capitalize">
                    {{ ucfirst($roleString) }}
                </p>
            </div>
        </div>
    </div>

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

    <div class="border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="flex items-center w-full px-6 py-4 text-sm font-medium text-gray-700 hover:bg-gray-50 transition group"
                    :class="{ 'justify-center px-4': $store.sidebar.collapsed }"
                    :title="$store.sidebar.collapsed ? 'Logout' : ''">
                <svg class="w-5 h-5 flex-shrink-0 transition-all duration-300"
                     :class="{ 'mr-0': $store.sidebar.collapsed, 'mr-3': !$store.sidebar.collapsed }"
                     fill="none" 
                     stroke="currentColor" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="transition-all duration-300" 
                      x-show="!$store.sidebar.collapsed"
                      x-transition>
                    Logout
                </span>
            </button>
        </form>
    </div>
</div>