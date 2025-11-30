<x-app-layout>
    <x-slot name="pageTitle">Edit Divisi</x-slot>
    <x-slot name="pageDescription">Edit informasi divisi</x-slot>

    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('admin.divisions.index') }}" class="hover:text-[#566534] transition">Division List</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">{{ $division->name }}</span>
        </div>
    </div>
    <div class="max-w-4xl mx-auto bg-white p-8 sm:rounded-lg shadow">

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-md">
                <b>Terjadi kesalahan:</b>
                <ul class="ml-4 mt-2 list-disc text-sm">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form 
            action="{{ route('admin.divisions.update', $division->id) }}" 
            method="POST" 
            class="space-y-6"
        >
            @csrf
            @method('PUT')
            <div>
                <x-breeze.input-label for="name" value="Nama Divisi" />
                <x-breeze.text-input
                    id="name"
                    name="name"
                    type="text"
                    class="block mt-1 w-full"
                    :value="old('name', $division->name)"
                    required
                    autofocus
                />
                <x-breeze.input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-breeze.input-label for="leader_id" value="Ketua Divisi" />

                <x-breeze.select-input
                    id="leader_id"
                    name="leader_id"
                    :options="$availableLeaders->pluck('full_name','id')"
                    placeholder="Pilih Ketua Divisi"
                    class="block mt-1 w-full"
                    :selected="old('leader_id', $division->leader_id)"
                    required
                />

                <x-breeze.input-error :messages="$errors->get('leader_id')" class="mt-2" />
            </div>
            <div>
                <x-breeze.input-label for="established_date" value="Tanggal Berdiri" />
                <x-breeze.text-input
                    id="established_date"
                    name="established_date"
                    type="date"
                    class="block mt-1 w-full"
                    :value="old('established_date', optional($division->established_date)->format('Y-m-d'))"
                    required
                />
                <x-breeze.input-error :messages="$errors->get('established_date')" class="mt-2" />
            </div>
            <div>
                <x-breeze.input-label for="description" value="Deskripsi (Opsional)" />
                <x-breeze.textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="block mt-1 w-full"
                >{{ old('description', $division->description) }}</x-breeze.textarea>

                <x-breeze.input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
            <div class="text-right flex gap-3 justify-end">
                
                <x-breeze.secondary-button
                    href="{{ route('admin.divisions.index') }}"
                    type="button"
                >
                    Batal
                </x-breeze.secondary-button>

                <x-breeze.primary-button>
                    Simpan Perubahan
                </x-breeze.primary-button>

            </div>

        </form>

    </div>

</x-app-layout>
