<x-app-layout>

    {{-- HEADER SLOT --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Manajemen Divisi</h1>

            <x-breeze.primary-button
                onclick="window.location='{{ route('admin.divisions.create') }}'">
                + Tambah Divisi Baru
            </x-breeze.primary-button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- FILTER CARD --}}
            <div class="bg-white shadow-xl sm:rounded-lg p-6 mb-8">

                <form method="GET" action="{{ route('admin.divisions.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                        {{-- SEARCH --}}
                        <div class="col-span-12 md:col-span-9">
                            <x-breeze.text-input 
                                type="text" 
                                name="search" 
                                placeholder="Cari nama divisi..."
                                value="{{ request('search') }}"
                                class="w-full" />
                        </div>

                        {{-- BUTTONS --}}
                        <div class="col-span-12 md:col-span-3 flex justify-beetwen gap-2">

                            <x-breeze.secondary-button 
                                type="submit" 
                                class="flex-1 justify-center !bg-gray-700 !text-white hover:!bg-gray-800">
                                Filter
                            </x-breeze.secondary-button>

                            <x-breeze.secondary-button 
                                type="button"
                                onclick="window.location='{{ route('admin.divisions.index') }}'"
                                class="flex-1 justify-center">
                                Reset
                            </x-breeze.secondary-button>

                        </div>
                    </div>
                </form>

            </div>

            {{-- TABLE --}}
            <x-ui.table :headers="['Divisi', 'Ketua', 'Anggota', 'Aksi']">

                @forelse($divisions as $division)

                    <tr class="hover:bg-gray-50">

                        {{-- NAME + DESCRIPTION --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            {{ $division->name }}
                            <p class="text-xs text-gray-500 mt-1">
                                {{ Str::limit($division->description, 60) }}
                            </p>
                        </td>

                        {{-- LEADER --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $division->leader->full_name ?? 'Belum ada' }}
                        </td>

                        {{-- MEMBERS COUNT --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $division->members_count }} orang
                        </td>

                        {{-- ACTION BUTTONS --}}
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">

                            <x-breeze.button-action 
                                href="{{ route('admin.divisions.show', $division->id) }}" 
                                color="blue">
                                Detail
                            </x-breeze.button-action>

                            <x-breeze.button-action 
                                href="{{ route('admin.divisions.edit', $division->id) }}" 
                                color="indigo">
                                Edit
                            </x-breeze.button-action>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4">
                            <x-ui.empty-state 
                                title="Tidak ada divisi ditemukan"
                                description="Belum ada divisi yang terdaftar di sistem"
                                :action="route('admin.divisions.create')"
                                actionText="+ Tambah Divisi Baru" />
                        </td>
                    </tr>

                @endforelse

            </x-ui.table>

            {{-- PAGINATION --}}
            <div class="mt-4">
                {{ $divisions->links() }}
            </div>

        </div>
    </div>

</x-app-layout>
