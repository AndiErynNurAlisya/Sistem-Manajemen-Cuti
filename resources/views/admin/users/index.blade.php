<x-app-layout>
    <x-slot name="pageTitle">Daftar Pengguna</x-slot>
    <x-slot name="pageDescription">Menampilkan daftar pengguna</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
            <div class="bg-white shadow-xl sm:rounded-lg p-6 mb-8 flex">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="col-span-12 md:col-span-3">
                            <x-breeze.text-input 
                                type="text" 
                                name="search" 
                                placeholder="Cari nama / email..."
                                value="{{ request('search') }}"
                                class="w-full" />
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <select name="division_id" class="block w-full sm:text-sm border-[#334124] focus:border-[#b5b89b] focus:ring-[#b5b89b] rounded-md shadow-sm">
                                <option value="">Semua Divisi</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" 
                                        {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-6 md:col-span-2">
                            <select name="role" class="block w-full shadow-sm sm:text-sm border-[#334124] focus:border-[#b5b89b] focus:ring-[#b5b89b] rounded-md ">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="leader" {{ request('role') == 'leader' ? 'selected' : '' }}>Leader</option>
                                <option value="hrd" {{ request('role') == 'hrd' ? 'selected' : '' }}>HRD</option>
                                <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                            </select>
                        </div>

                        <div class="col-span-6 md:col-span-2">
                            <select name="is_active" class="block w-full shadow-sm sm:text-sm border-[#334124] focus:border-[#b5b89b] focus:ring-[#b5b89b] rounded-md ">
                                <option value="">Status</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

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

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <x-ui.sortable-header 
                                    column="full_name" 
                                    label="Nama"
                                    :sortBy="request('sort_by')"
                                    :sortOrder="request('sort_order')" />

                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>

                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role
                                </th>

                                <x-ui.sortable-header 
                                    column="division" 
                                    label="Divisi"
                                    :sortBy="request('sort_by')"
                                    :sortOrder="request('sort_order')" />

                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>

                                <x-ui.sortable-header 
                                    column="join_date" 
                                    label="Tgl Bergabung"
                                    :sortBy="request('sort_by')"
                                    :sortOrder="request('sort_order')" />

                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->full_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <x-ui.role-badge :role="$user->role->value" />
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->division->name ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <x-ui.status-badge :active="$user->is_active" />
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatDate($user->join_date) }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <x-breeze.button-action 
                                                href="{{ route('admin.users.show', $user->id) }}" 
                                                color="army">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </x-breeze.button-action>

                                            <x-breeze.button-action 
                                                href="{{ route('admin.users.edit', $user->id) }}" 
                                                color="dashboard">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M11 5h2m4 4l-8 8H5v-4l8-8z" />
                                                </svg>
                                            </x-breeze.button-action>

                                            @if($user->canBeDeleted())
                                            <x-breeze.button-action 
                                                href="{{ route('admin.users.destroy', $user->id) }}" 
                                                color="danger" 
                                                method="DELETE" 
                                                confirm="Hapus user ini?">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-6-4h6a2 2 0 012 2v0H7a2 2 0 012-2z" />
                                                </svg>
                                            </x-breeze.button-action>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12">
                                        <x-ui.empty-state 
                                            title="Tidak ada data user"
                                            description="Belum ada user yang terdaftar atau tidak ada hasil yang sesuai dengan filter"
                                            actionUrl="{{ route('admin.users.create') }}"
                                            actionText="+ Tambah User Baru" />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $users->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
</x-app-layout>