<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail User') }}
            </h2>
            <!-- <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Kembali ke Daftar User
            </a> -->
            <x-breeze.back-link href="{{ route('admin.users.index') }}" label="← Kembali ke Daftar User" />
            
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-md">

        {{-- GRID 2 KOLOM --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- KOLOM KIRI (4 info) --}}
            <div class="space-y-6">

                <x-ui.field label="Nama Lengkap">
                    {{ $user->full_name }}
                </x-ui.field>

                <x-ui.field label="Username">
                    {{ $user->name }}
                </x-ui.field>

                <x-ui.field label="Email">
                    {{ $user->email }}
                </x-ui.field>

                <x-ui.field label="Role">
                    <x-ui.role-badge :role="$user->role->value" />
                </x-ui.field>

            </div>

            {{-- KOLOM KANAN (3 info) --}}
            <div class="space-y-6">

                <x-ui.field label="Divisi">
                    {{ $user->division->name ?? '-' }}
                </x-ui.field>

                <x-ui.field label="Tanggal Bergabung">
                    {{ $user->join_date?->format('d M Y') ?? '-' }}
                </x-ui.field>

                <x-ui.field label="Status">
                    <x-ui.status-badge :active="$user->is_active" />
                </x-ui.field>

            </div>

        </div>

    </div>

</x-app-layout>
