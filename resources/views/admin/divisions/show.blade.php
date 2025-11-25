<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">
                Detail Divisi: {{ $division->name }}
            </h1>
 <x-breeze.back-link href="{{ route('admin.divisions.index') }}" label="← Kembali ke Daftar Divisi" />

        </div>
    </x-slot>

    {{-- DIVISION INFO --}}
    <div class="bg-white shadow-xl sm:rounded-lg p-8 mb-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <x-ui.field label="Nama Divisi">
                {{ $division->name }}
            </x-ui.field>

            <x-ui.field label="Ketua Divisi">
                @if($division->leader)
                    <div>
                        <p class="font-semibold text-blue-600">{{ $division->leader->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $division->leader->email }}</p>
                    </div>
                @else
                    <span class="text-gray-400">Belum ada Ketua</span>
                @endif
            </x-ui.field>

            <x-ui.field label="Deskripsi" class="md:col-span-2">
                {{ $division->description ?: '-' }}
            </x-ui.field>

            <x-ui.field label="Dibuat Pada">
                {{ $division->created_at->format('d M Y') }}
            </x-ui.field>

        </div>
    </div>

    {{-- MEMBERS SECTION --}}
    <div class="max-w-7xl mx-auto">

        <h2 class="text-xl font-bold text-gray-800 mb-4">
            Anggota Divisi ({{ $division->members->count() }} orang)
        </h2>

        {{-- ADD MEMBER --}}
        <div class="bg-gray-50 shadow sm:rounded-lg p-6 mb-8">

            <form method="POST" 
                  action="{{ route('admin.divisions.members.add', $division->id) }}"
                  class="flex flex-col md:flex-row gap-4 items-end">

                @csrf

                <div class="flex-1">
                    <x-ui.field label="Tambah Anggota Baru">
                        <x-breeze.select-input
                            name="user_id"
                            id="user_id"
                            :options="$availableUsers->pluck('full_name', 'id')"
                            placeholder="Pilih karyawan"
                            required
                        />
                        <x-breeze.input-error :messages="$errors->get('user_id')" class="mt-2" />
                    </x-ui.field>
                </div>

                <x-breeze.primary-button >
                    Tambahkan
                </x-breeze.primary-button>

            </form>

        </div>

        {{-- MEMBER LIST --}}
        @if($division->members->isEmpty())

            <x-ui.empty-state 
                title="Belum ada anggota"
                description="Tambah anggota menggunakan form di atas."
            />

        @else

            <x-ui.table :headers="['Nama', 'Role', 'Sisa Cuti', 'Aksi']">

                @foreach($division->members as $member)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $member->full_name }}
                        </td>

                        <td class="px-6 py-4 text-sm">
                            <x-ui.role-badge :role="$member->role->value" />
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $member->leaveQuota->remaining_quota ?? 'N/A' }} hari
                        </td>

                        <td class="px-6 py-4 text-right text-sm">

                            @if($member->id !== $division->leader_id)
                                <form method="POST"
                                      action="{{ route('admin.divisions.members.remove', [$division->id, $member->id]) }}"
                                      onsubmit="return confirm('Hapus {{ $member->full_name }} dari divisi ini?');">

                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-600 hover:text-red-900">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">Ketua Divisi</span>
                            @endif

                        </td>

                    </tr>

                @endforeach

            </x-ui.table>

        @endif

    </div>

</x-app-layout>
