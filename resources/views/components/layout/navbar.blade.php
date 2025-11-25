{{-- resources/views/components/layout/navbar.blade.php --}}

<nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left: Mobile Menu Button -->
            <div class="flex items-center lg:hidden">
                <button @click="sidebarOpen = true" 
                        class="text-gray-500 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Center: Breadcrumb or Page Title (Optional) -->
            <div class="flex items-center flex-1 lg:ml-0 ml-4">
                <div class="text-sm text-gray-500">
                    {{ $breadcrumb ?? '' }}
                </div>
            </div>

            <!-- Right: User Menu & Notifications -->
            <div class="flex items-center space-x-4">
                <!-- Notification Bell -->
                <button class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <!-- Badge -->
                    @if(isset($notificationCount) && $notificationCount > 0)
                        <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                    @endif
                </button>

                <!-- User Dropdown -->
                <x-breeze.dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-2">
                                    <span class="text-indigo-600 font-semibold text-xs">
                                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                                    </span>
                                </div>
                                <span class="hidden md:block">{{ auth()->user()->name ?? 'User' }}</span>
                                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Profile -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>

                        <x-breeze.dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-breeze.dropdown-link>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-breeze.dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-breeze.dropdown-link>
                        </form>
                    </x-slot>
                </x-breeze.dropdown>
            </div>
        </div>
    </div>
</nav>