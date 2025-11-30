<x-app-layout>
    <x-slot name="pageTitle">Detail Divisi</x-slot>
    <x-slot name="pageDescription">Informasi terkait divisi</x-slot>

    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('admin.divisions.index') }}" class="hover:text-[#566534] transition">Division List</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">{{ $division->name }}</span>
        </div>
    </div>

    <div >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-xl overflow-hidden mb-6">
                
                <div class="relative bg-gradient-to-br from-[#566534] via-[#6b7d44] to-[#b5b89b] h-32"></div>
                
                <div class="relative px-8 pb-8">
                    
                    <div class="flex flex-col items-center -mt-16 mb-8">
                        @if($division->leader)
                            <img 
                                src="{{ getProfilePhotoUrl($division->leader) }}" 
                                alt="{{ $division->leader->full_name }}"
                                class="h-28 w-28 rounded-full object-cover ring-4 ring-white shadow-xl"
                            >
                            
                            <h2 class="mt-4 text-xl font-bold text-gray-900">
                                {{ $division->leader->full_name }}
                            </h2>
                            
                            <div class="mt-2 px-4 py-1 bg-[#b5b89b] text-[#334124] rounded-full">
                                <span class="text-xs font-semibold uppercase">Ketua Divisi</span>
                            </div>
                            
                            <a href="mailto:{{ $division->leader->email }}" 
                               class="mt-2 text-sm text-[#566534] hover:text-[#334124] transition">
                                {{ $division->leader->email }}
                            </a>
                        @else
                            <div class="h-28 w-28 rounded-full bg-gray-200 ring-4 ring-white shadow-xl flex items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <p class="mt-4 text-gray-500 italic">Belum ada ketua divisi</p>
                        @endif
                    </div>

                    <div class="border-t border-gray-200 mb-8"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nama Divisi</label>
                            <p class="text-base font-semibold text-gray-900">{{ $division->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Total Anggota</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                {{ $division->members->count() }} orang
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Dibentuk</label>
                            <p class="text-sm text-gray-700">{{ formatDate($division->created_at, 'd F Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Terakhir Diupdate</label>
                            <p class="text-sm text-gray-700">{{ formatDate($division->updated_at, 'd F Y') }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Deskripsi</label>
                            <p class="text-sm text-gray-700">
                                {{ $division->description ?: 'Tidak ada deskripsi' }}
                            </p>
                        </div>

                    </div>

                </div>
            </div>
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Anggota Divisi ({{ $division->members->count() }} orang)
                    </h2>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <form method="POST" 
                            action="{{ route('admin.divisions.members.add', $division->id) }}"
                            class="flex flex-col sm:flex-row gap-3 items-end">
                        @csrf

                        <div class="flex-1 w-full">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Tambah Anggota Baru
                            </label>
                            <select name="user_id" 
                                    id="user_id"
                                    required
                                    class="block w-full text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->full_name }} ({{ ucfirst($user->role->value) }})
                                    </option>
                                @endforeach
                            </select>
                            <x-breeze.input-error :messages="$errors->get('user_id')" class="mt-1" />
                        </div>

                        <x-breeze.primary-button class="w-full sm:w-auto">
                            + Tambahkan
                        </x-breeze.primary-button>
                    </form>
                </div>

                @if($division->members->isEmpty())
                    <div class="p-12">
                        <x-ui.empty-state 
                            title="Belum ada anggota"
                            description="Tambah anggota menggunakan form di atas" />
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tgl Bergabung
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sisa Cuti
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($division->members as $member)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $member->full_name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $member->email }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <x-ui.role-badge :role="$member->role->value" />
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <x-ui.status-badge :active="$member->is_active" />
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate($member->join_date) }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            @if($member->leaveQuota)
                                                <span class="font-medium">{{ $member->leaveQuota->remaining_quota }}</span> hari
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            @if($member->id !== $division->leader_id)
                                                <form method="POST"
                                                        action="{{ route('admin.divisions.members.remove', [$division->id, $member->id]) }}"
                                                        onsubmit="return confirm('Hapus {{ $member->full_name }} dari divisi ini?');"
                                                        class="inline">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 font-medium transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    Ketua Divisi
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
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