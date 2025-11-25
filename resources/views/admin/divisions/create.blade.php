<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">
                Tambah Divisi Baru
            </h1>

  <x-breeze.back-link href="{{ route('admin.divisions.index') }}" label="← Kembali ke Daftar Divisi" />
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white p-8 sm:rounded-lg shadow">

        <form action="{{ route('admin.divisions.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Nama Divisi --}}
            <div>
                <x-breeze.input-label for="name" value="Nama Divisi" />
                <x-breeze.text-input 
                    id="name" name="name" type="text" 
                    class="block mt-1 w-full"
                    :value="old('name')" required autofocus
                />
                <x-breeze.input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            {{-- Ketua Divisi --}}
            <div>
                <x-breeze.input-label for="leader_id" value="Ketua Divisi" />

                <x-breeze.select-input
                    id="leader_id"
                    name="leader_id"
                    :options="$availableLeaders->pluck('full_name','id')"
                    placeholder="Pilih Ketua Divisi"
                    class="block mt-1 w-full"
                    :selected="old('leader_id')"
                    required
                />

                <x-breeze.input-error :messages="$errors->get('leader_id')" class="mt-2" />
            </div>

            {{-- Tanggal Berdiri --}}
            <div>
                <x-breeze.input-label for="established_date" value="Tanggal Berdiri" />
                <x-breeze.text-input
                    id="established_date"
                    name="established_date"
                    type="date"
                    class="block mt-1 w-full"
                    :value="old('established_date')"
                    required
                />
                <x-breeze.input-error :messages="$errors->get('established_date')" class="mt-2" />
            </div>

            {{-- Deskripsi --}}
            <div>
                <x-breeze.input-label for="description" value="Deskripsi (Opsional)" />
                <x-breeze.textarea 
                    id="description"
                    name="description"
                    rows="3"
                    class="block mt-1 w-full"
                >{{ old('description') }}</x-breeze.textarea>
                <x-breeze.input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            {{-- Tombol Submit --}}
            <div class="text-right">
                <x-breeze.primary-button>
                    Simpan Divisi
                </x-breeze.primary-button>
            </div>

        </form>

    </div>

</x-app-layout>
