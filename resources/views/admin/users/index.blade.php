<x-app-layout>
    {{-- HEADER SLOT --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Daftar User</h1>
                <x-breeze.primary-button
                    onclick="window.location='{{ route('admin.users.create') }}'"
                    type="button">
                    + Tambah User
                </x-breeze.primary-button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
            
            {{-- Action Button --}}

            {{-- FILTER CARD --}}
            <div class="bg-white shadow-xl sm:rounded-lg p-6 mb-8 flex">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                        {{-- Search --}}
                        <div class="col-span-12 md:col-span-3">
                            <x-breeze.text-input 
                                type="text" 
                                name="search" 
                                placeholder="Cari nama / email..."
                                value="{{ request('search') }}"
                                class="w-full" />
                        </div>

                        {{-- Division Filter --}}
                        <div class="col-span-6 md:col-span-2">
                            <select name="division_id" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Divisi</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" 
                                        {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Role Filter --}}
                        <div class="col-span-6 md:col-span-2">
                            <select name="role" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="leader" {{ request('role') == 'leader' ? 'selected' : '' }}>Leader</option>
                                <option value="hrd" {{ request('role') == 'hrd' ? 'selected' : '' }}>HRD</option>
                                <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div class="col-span-6 md:col-span-2">
                            <select name="is_active" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Status</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        {{-- Filter & Reset Buttons --}}
                        <div class="col-span-12 md:col-span-2 flex gap-2">
                            <x-breeze.secondary-button type="submit" class="flex-1 justify-center !bg-gray-600 !text-white hover:!bg-gray-700">
                                Filter
                            </x-breeze.secondary-button>

                            <x-breeze.secondary-button 
                                type="button"
                                onclick="window.location='{{ route('admin.users.index') }}'"
                                class="flex-1 justify-center">
                                Reset
                            </x-breeze.secondary-button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- USER TABLE --}}
            <x-ui.table :headers="['Nama', 'Email', 'Role', 'Divisi', 'Status', 'Join Date', 'Aksi']">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50">
                        
                        {{-- NAME --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->full_name }}
                        </td>
                        
                        {{-- EMAIL --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->email }}
                        </td>

                        {{-- ROLE BADGE (component) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <x-ui.role-badge :role="$user->role->value" />
                        </td>

                        {{-- DIVISION (no badge) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->division->name ?? '-' }}
                        </td>

                        {{-- STATUS BADGE (component) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <x-ui.status-badge :active="$user->is_active" />
                        </td>

                        {{-- JOIN DATE --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ formatDate($user->join_date) }}
                        </td>

                        {{-- ACTION BUTTONS --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-breeze.button-action href="{{ route('admin.users.show', $user->id) }}" color="blue">
                                Detail
                            </x-breeze.button-action>

                            <x-breeze.button-action href="{{ route('admin.users.edit', $user->id) }}" color="indigo">
                                Edit
                            </x-breeze.button-action>

                            <x-breeze.button-action 
                                href="{{ route('admin.users.destroy', $user->id) }}" 
                                color="red" 
                                method="DELETE" 
                                confirm="[translate:Hapus user ini?]">
                                Hapus
                            </x-breeze.button-action>
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="7">
                            <x-ui.empty-state 
                                title="Tidak ada data user"
                                description="Belum ada user yang terdaftar di sistem"
                                :action="route('admin.users.create')"
                                actionText="+ Tambah User Pertama" />
                        </td>
                    </tr>
                @endforelse
            </x-ui.table>

            {{-- PAGINATION --}}
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
