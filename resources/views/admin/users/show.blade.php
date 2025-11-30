<x-app-layout>
    <x-slot name="pageTitle">Detail User</x-slot>
    <x-slot name="pageDescription">Informasi lengkap data user</x-slot>

    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('admin.users.index') }}" class="hover:text-[#566534] transition">User List</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">{{ $user->full_name }}</span>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            
            <div class="relative bg-gradient-to-br from-[#566534] via-[#6b7d44] to-[#b5b89b] h-40"></div>
            
            <div class="relative px-8 pb-8">
                
                <div class="flex flex-col items-center -mt-20 mb-8">
                    <img 
                        src="{{ getProfilePhotoUrl($user) }}" 
                        alt="{{ $user->full_name }}"
                        class="h-32 w-32 rounded-full object-cover ring-4 ring-white shadow-xl"
                    >
                    
                    <h1 class="mt-4 text-2xl font-bold text-gray-900">
                        {{ $user->full_name }}
                    </h1>
                    
                    <div class="mt-2 px-4 py-1.5 bg-[#b5b89b] text-[#334124] rounded-full">
                        <span class="text-sm font-semibold uppercase">
                            {{ $user->role->value }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-200 mb-8"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                    
                    <x-ui.field label="Username">
                        <span class="text-gray-900 font-medium">{{ $user->name }}</span>
                    </x-ui.field>

                    <x-ui.field label="Divisi">
                        <span class="text-gray-900 font-medium">{{ $user->division->name ?? '-' }}</span>
                    </x-ui.field>

                    <x-ui.field label="Email">
                        <a href="mailto:{{ $user->email }}" 
                           class="text-[#566534] hover:text-[#b5b89b] font-medium transition">
                            {{ $user->email }}
                        </a>
                    </x-ui.field>

                    <x-ui.field label="Tanggal Bergabung">
                        <span class="text-gray-900 font-medium">
                            {{ $user->join_date?->format('d M Y') ?? '-' }}
                        </span>
                    </x-ui.field>

                    <x-ui.field label="Masa Kerja">
                        <span class="text-gray-900 font-medium">
                            {{ $user->join_date ? $user->join_date->diffForHumans(null, true) : '-' }}
                        </span>
                    </x-ui.field>

                    <x-ui.field label="Status">
                        @if($user->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-semibold">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-800 text-sm font-semibold">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Nonaktif
                            </span>
                        @endif
                    </x-ui.field>

                </div>

                <div class="flex justify-between items-center mt-10 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="inline-flex items-center px-5 py-2.5 text-white bg-[#566534] hover:bg-[#334124] rounded-lg font-medium transition shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit User
                    </a>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>