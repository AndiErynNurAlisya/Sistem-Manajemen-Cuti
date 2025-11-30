<nav class="bg-[#f6f4f0] sticky top-0 z-30">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center lg:hidden">
                <button @click="sidebarOpen = true" 
                        class="text-gray-500 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <div class="flex items-center flex-1 lg:ml-0 ml-4">
                @if(isset($pageTitle))
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">{{ $pageTitle }}</h1>
                        @if(isset($pageDescription))
                            <p class="text-xs text-gray-500 mt-0.5 hidden sm:block">{{ $pageDescription }}</p>
                        @endif
                    </div>
                @else
                    <div class="text-sm text-gray-500">
                        {{ $breadcrumb ?? '' }}
                    </div>
                @endif
            </div>

            <div class="flex items-center space-x-4">
                <x-breeze.dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition">
                            <div class="flex items-center">
                                <img 
                                    src="{{ getProfilePhotoUrl(auth()->user()) }}" 
                                    alt="{{ auth()->user()->name }}"
                                    class="w-8 h-8 rounded-full object-cover ring-2 ring-[#b5b89b] mr-2"
                                >
                                <span class="hidden md:block">{{ auth()->user()->name ?? 'User' }}</span>
                                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <img 
                                    src="{{ getProfilePhotoUrl(auth()->user()) }}" 
                                    alt="{{ auth()->user()->name }}"
                                    class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-200"
                                >
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <x-breeze.dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-breeze.dropdown-link>

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