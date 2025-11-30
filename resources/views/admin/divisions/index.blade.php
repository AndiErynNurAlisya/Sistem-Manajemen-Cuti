<x-app-layout>
    <x-slot name="pageTitle">Daftar Divisi</x-slot>
    <x-slot name="pageDescription">Menampilkan daftar divisi</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl sm:rounded-lg p-6 mb-8">
                <form method="GET" action="{{ route('admin.divisions.index') }}">
                    <div class="flex flex-col lg:flex-row gap-4 items-end">

                        <div class="flex-1 w-full">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Divisi
                            </label>
                            <x-breeze.text-input 
                                type="text" 
                                id="search"
                                name="search" 
                                placeholder="Cari nama divisi..."
                                value="{{ request('search') }}"
                                class="w-full" />
                        </div>

                        <div class="flex-1 w-full">
                            <label for="leader" class="block text-sm font-medium text-gray-700 mb-1">
                                Ketua Divisi
                            </label>
                            <x-breeze.text-input 
                                type="text" 
                                id="leader"
                                name="leader" 
                                placeholder="Cari ketua divisi..."
                                value="{{ request('leader') }}"
                                class="w-full" />
                        </div>

                        <div class="flex-1 w-full">
                            <label for="member_count" class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah Anggota
                            </label>
                            <select 
                                id="member_count"
                                name="member_count" 
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Semua Jumlah</option>
                                <option value="0" {{ request('member_count') == '0' ? 'selected' : '' }}>
                                    Tidak Ada Anggota
                                </option>
                                <option value="1-5" {{ request('member_count') == '1-5' ? 'selected' : '' }}>
                                    1-5 Anggota
                                </option>
                                <option value="6-10" {{ request('member_count') == '6-10' ? 'selected' : '' }}>
                                    6-10 Anggota
                                </option>
                                <option value="11-20" {{ request('member_count') == '11-20' ? 'selected' : '' }}>
                                    11-20 Anggota
                                </option>
                                <option value="21-50" {{ request('member_count') == '21-50' ? 'selected' : '' }}>
                                    21-50 Anggota
                                </option>
                                <option value="50+" {{ request('member_count') == '50+' ? 'selected' : '' }}>
                                    50+ Anggota
                                </option>
                            </select>
                        </div>

                        <div class="flex gap-2 w-full lg:w-auto">
                            <x-breeze.secondary-button 
                                type="button"
                                onclick="window.location='{{ route('admin.divisions.index') }}'"
                                class="justify-center flex-1 lg:flex-initial">
                                RESET
                            </x-breeze.secondary-button>

                            <x-breeze.secondary-button 
                                type="submit" 
                                class="justify-center flex-1 lg:flex-initial !bg-gray-700 !text-white hover:!bg-gray-800">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                FILTER
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
                                    column="name" 
                                    label="Nama Divisi"
                                    :sortBy="request('sort_by')"
                                    :sortOrder="request('sort_order')" />

                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ketua Divisi
                                </th>

                                <x-ui.sortable-header 
                                    column="members_count" 
                                    label="Anggota"
                                    :sortBy="request('sort_by')"
                                    :sortOrder="request('sort_order')" />

                                <x-ui.sortable-header 
                                    column="created_at" 
                                    label="Tgl Dibentuk"
                                    :sortBy="request('sort_by')"
                                    :sortOrder="request('sort_order')" />

                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($divisions as $division)
                                <tr class="hover:bg-gray-50 transition-colors">

                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $division->name }}
                                        </div>
                                        @if($division->description)
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ Str::limit($division->description, 60) }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($division->leader)
                                            <div class="font-medium text-gray-900">
                                                {{ $division->leader->full_name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $division->leader->email }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Belum ada ketua</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $division->members_count }} orang
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatDate($division->created_at) }}
                                    </td>

                                    <div class="inline-flex items-center space-x-1">
                                    <td>
                                    <a href="{{ route('admin.divisions.show', $division->id) }}" title="Detail Divisi">
                                        <button type="button" 
                                            class="inline-flex items-center p-1.5 text-[#566534] hover:text-[#3f4a24] transition rounded-md"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </a>

                                    <a href="{{ route('admin.divisions.edit', $division->id) }}" title="Edit Divisi">
                                        <button type="button" 
                                            class="inline-flex items-center p-1.5 text-[#566534] hover:text-[#3f4a24] transition rounded-md"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                    </a>

                                    <button 
                                        type="button"
                                        @click="$dispatch('open-delete-modal', { divisionId: {{ $division->id }}, divisionName: '{{ $division->name }}' })"
                                        title="Hapus Divisi"
                                        class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 transition rounded-md"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-6-4h6a2 2 0 012 2v0H7a2 2 0 012-2z" />
                                        </svg>
                                    </button>
                                    </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12">
                                        <x-ui.empty-state 
                                            title="Tidak ada divisi ditemukan"
                                            description="Belum ada divisi yang terdaftar atau tidak ada hasil yang sesuai dengan filter"
                                            actionUrl="{{ route('admin.divisions.create') }}"
                                            actionText="+ Tambah Divisi Baru" />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $divisions->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>

    <div x-data="{ 
        showModal: false, 
        divisionId: null, 
        divisionName: '', 
        confirmationInput: '' 
    }"
        @open-delete-modal.window="
            showModal = true; 
            divisionId = $event.detail.divisionId; 
            divisionName = $event.detail.divisionName;
            confirmationInput = '';
        "
        x-show="showModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
            @click="showModal = false"></div>

        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6"
                @click.away="showModal = false">
                
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>

                <h3 class="text-lg font-medium text-gray-900 text-center mb-2">
                    Konfirmasi Hapus Divisi
                </h3>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 text-center mb-4">
                        Anda akan menghapus divisi <strong x-text="divisionName"></strong>. 
                        Semua anggota divisi ini akan kehilangan atribut divisi mereka.
                    </p>

                    <div class="bg-amber-50 border-l-4 border-amber-400 p-3 mb-4">
                        <p class="text-sm text-amber-700">
                            ⚠️ Tindakan ini tidak dapat dibatalkan!
                        </p>
                    </div>

                    <p class="text-sm text-gray-700 mb-2">
                        Ketik <strong x-text="divisionName"></strong> untuk konfirmasi:
                    </p>

                    <form :action="`{{ route('admin.divisions.index') }}/${divisionId}`" 
                            method="POST"
                            @submit.prevent="if(confirmationInput === divisionName) { $el.submit(); } else { alert('Nama divisi tidak sesuai!'); }">
                        @csrf
                        @method('DELETE')

                        <input type="text" 
                                x-model="confirmationInput"
                                name="division_name_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 text-sm"
                                placeholder="Ketik nama divisi">

                        <div class="flex gap-3 mt-6">
                            <button type="button"
                                    @click="showModal = false"
                                    class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-medium transition">
                                Batal
                            </button>

                            <button type="submit"
                                    :disabled="confirmationInput !== divisionName"
                                    :class="confirmationInput === divisionName ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-300 cursor-not-allowed'"
                                    class="flex-1 px-4 py-2 text-white rounded-md text-sm font-medium transition">
                                Hapus Divisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>