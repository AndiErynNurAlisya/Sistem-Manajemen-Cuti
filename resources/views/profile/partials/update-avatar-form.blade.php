
<section>
    <header>
        <h2 class="text-lg font-medium" style="color: #334124;">
            {{ __('Foto Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Upload foto profil Anda. Format yang diizinkan: JPG, JPEG, PNG. Maksimal 2MB.') }}
        </p>
    </header>

    <div class="mt-6" x-data="{ 
        photoPreview: null,
        photoName: null,
        updatePhotoPreview() {
            const photo = $refs.photo.files[0];
            if (photo) {
                this.photoName = photo.name;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };
                reader.readAsDataURL(photo);
            }
        }
    }">

        <div class="flex items-center gap-6">
            <div class="flex-shrink-0">
                <img 
                    :src="photoPreview || '{{ getProfilePhotoUrl($user) }}'" 
                    alt="{{ $user->name }}"
                    class="h-24 w-24 rounded-full object-cover ring-4 ring-gray-100"
                >
            </div>

            <div class="flex-1">
                <form method="post" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <input 
                            type="file" 
                            name="profile_photo" 
                            id="profile_photo"
                            x-ref="photo"
                            @change="updatePhotoPreview()"
                            accept="image/jpeg,image/jpg,image/png"
                            class="hidden"
                        >
                        
                        <label for="profile_photo" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150" style="border-color: #566534; color: #334124;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Pilih Foto Baru
                        </label>
                        
                        <span x-show="photoName" x-text="photoName" class="ml-3 text-sm text-gray-600"></span>
                    </div>

                    <x-breeze.input-error class="mt-2" :messages="$errors->get('profile_photo')" />

                    <div class="flex items-center gap-4" x-show="photoPreview">
                        <x-breeze.primary-button style="background-color: #334124;">
                            {{ __('Upload Foto') }}
                        </x-breeze.primary-button>

                        <button type="button" @click="photoPreview = null; photoName = null; $refs.photo.value = null;" class="text-sm text-gray-600 hover:text-gray-900">
                            Batal
                        </button>
                    </div>
                </form>

                @if($user->profile_photo)
                    <form method="post" action="{{ route('profile.photo.delete') }}" class="mt-4">
                        @csrf
                        @method('delete')

                        <x-breeze.danger-button 
                            type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil?')"
                        >
                            {{ __('Hapus Foto') }}
                        </x-breeze.danger-button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>