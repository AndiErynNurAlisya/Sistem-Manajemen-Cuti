<x-app-layout>
    <x-slot name="pageTitle">Tambah Pengguna</x-slot>
    <x-slot name="pageDescription">Tambah pengguna baru</x-slot>

    <div >
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <x-breeze.input-label for="full_name" value="Nama Lengkap" />
                            <x-breeze.text-input 
                                id="full_name" 
                                name="full_name" 
                                type="text" 
                                class="mt-1 block w-full" 
                                :value="old('full_name')" 
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
                                :value="old('name')" 
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
                                :value="old('email')" 
                                required 
                            />
                            <x-breeze.input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-breeze.input-label for="role" value="Role" />
                            <select 
                                id="role" 
                                name="role" 
                                class="mt-1 block w-full border-[#334124] focus:border-[#b5b89b] focus:ring-[#b5b89b] rounded-md shadow-sm"
                                required>
                                <option value="">-- Pilih Role --</option>
                                @foreach(\App\Enums\UserRole::cases() as $role)
                                    @php
                                        if ($role->value === 'admin' && $adminExists) continue;

                                        if ($role->value === 'hrd' && $hrdExists) continue;
                                    @endphp

                                    <option value="{{ $role->value }}" {{ old('role') === $role->value ? 'selected' : '' }}>
                                        {{ $role->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <x-breeze.input-error :messages="$errors->get('role')" class="mt-2" />
                            
                            @if($adminExists || $hrdExists)
                                <div class="mt-2 text-sm text-amber-600">
                                    <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    @if($adminExists) Role Admin sudah ada. @endif
                                    @if($hrdExists) Role HRD sudah ada. @endif
                                </div>
                            @endif
                        </div>

                        <div>
                            <x-breeze.input-label for="division_id" value="Divisi" />
                            <select 
                                id="division_id" 
                                name="division_id" 
                                class="mt-1 block w-full border-[#334124] focus:border-[#b5b89b] focus:ring-[#b5b89b] rounded-md shadow-sm">
                                <option value="">-- Pilih Divisi (Opsional) --</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-breeze.input-error :messages="$errors->get('division_id')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Wajib untuk Employee dan Leader</p>
                        </div>

                        <div>
                            <x-breeze.input-label for="join_date" value="Tanggal Bergabung" />
                            <x-breeze.text-input 
                                id="join_date" 
                                name="join_date" 
                                type="date" 
                                class="mt-1 block w-full" 
                                :value="old('join_date', date('Y-m-d'))" 
                                required 
                            />
                            <x-breeze.input-error :messages="$errors->get('join_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-breeze.input-label for="password" value="Password" />
                            <x-breeze.text-input 
                                id="password" 
                                name="password" 
                                type="password" 
                                class="mt-1 block w-full" 
                                required 
                                autocomplete="new-password"
                            />
                            <x-breeze.input-error :messages="$errors->get('password')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Minimal 8 karakter</p>
                        </div>

                        <div>
                            <x-breeze.input-label for="password_confirmation" value="Konfirmasi Password" />
                            <x-breeze.text-input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                class="mt-1 block w-full" 
                                required 
                                autocomplete="new-password"
                            />
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input 
                                    id="is_active" 
                                    name="is_active" 
                                    type="checkbox" 
                                    value="1"
                                    {{ old('is_active', 1) ? 'checked' : '' }}
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
                                Simpan User
                            </x-b-breeze.primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>