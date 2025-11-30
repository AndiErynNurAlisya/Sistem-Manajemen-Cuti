<x-app-layout>
    <x-slot name="pageTitle">Edit Pengguna</x-slot>
    <x-slot name="pageDescription">Edit informasi pengguna</x-slot>

    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('admin.users.index') }}" class="hover:text-[#566534] transition">User List</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">{{ $user->full_name }}</span>
        </div>
    </div>

    <div >
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-breeze.input-label for="full_name" value="Nama Lengkap" />
                            <x-breeze.text-input 
                                id="full_name" 
                                name="full_name" 
                                type="text" 
                                class="mt-1 block w-full" 
                                :value="old('full_name', $user->full_name)" 
                                required 
                                autofocus 
                            />
                            <x-breeze.input-error :messages="$errors->get('full_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-breeze.input-label for="name" value="Username" />
                            <x-breeze.text-input 
                                id="name" 
                                name="name" 
                                type="text" 
                                class="mt-1 block w-full" 
                                :value="old('name', $user->name)" 
                                required 
                            />
                            <x-breeze.input-error :messages="$errors->get('name')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Username digunakan untuk login</p>
                        </div>

                        <div>
                            <x-breeze.input-label for="email" value="Email" />
                            <x-breeze.text-input 
                                id="email" 
                                name="email" 
                                type="email" 
                                class="mt-1 block w-full" 
                                :value="old('email', $user->email)" 
                                required 
                            />
                            <x-breeze.input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-breeze.input-label for="role" value="Role" />

                            <select 
                                id="role" 
                                name="role" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                @foreach(\App\Enums\UserRole::cases() as $role)
                                    @php
                                        if ($role->value === 'admin' && $adminExists && $user->role->value !== 'admin') continue;

                                        if ($role->value === 'hrd' && $hrdExists && $user->role->value !== 'hrd') continue;
                                    @endphp

                                    <option 
                                        value="{{ $role->value }}" 
                                        {{ old('role', $user->role->value) === $role->value ? 'selected' : '' }}>
                                        {{ $role->label() }}
                                    </option>
                                @endforeach
                            </select>

                            <x-breeze.input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div>
                            <x-breeze.input-label for="division_id" value="Divisi" />
                            <select 
                                id="division_id" 
                                name="division_id" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Divisi (Opsional) --</option>

                                @foreach($divisions as $division)
                                    <option 
                                        value="{{ $division->id }}"
                                        {{ old('division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-breeze.input-error :messages="$errors->get('division_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-breeze.input-label for="join_date" value="Tanggal Bergabung" />
                            <x-breeze.text-input 
                                id="join_date" 
                                name="join_date" 
                                type="date" 
                                class="mt-1 block w-full" 
                                :value="old('join_date', $user->join_date?->format('Y-m-d'))" 
                                required 
                            />
                            <x-breeze.input-error :messages="$errors->get('join_date')" class="mt-2" />
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input 
                                    id="is_active" 
                                    name="is_active" 
                                    type="checkbox" 
                                    value="1"
                                    {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2"
                                >
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-gray-700">Aktifkan User</label>
                                <p class="text-gray-500">User dapat login jika dicentang</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-200"></div>

                        <div class="flex items-center justify-end gap-4">
                            <x-breeze.secondary-button type="button" onclick="window.history.back()">
                                Batal
                            </x-breeze.secondary-button>

                            <x-breeze.primary-button>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
                            </x-breeze.primary-button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>