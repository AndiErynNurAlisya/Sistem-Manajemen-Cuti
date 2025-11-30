
<section>
    <header>
        <h2 class="text-lg font-medium" style="color: #334124;">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui informasi profil dan alamat email akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-breeze.input-label for="name" :value="__('Nama')" />
            <x-breeze.text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-breeze.input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-breeze.input-label for="full_name" :value="__('Nama Lengkap')" />
            <x-breeze.text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name', $user->full_name)" autocomplete="name" />
            <x-breeze.input-error class="mt-2" :messages="$errors->get('full_name')" />
        </div>

        <div>
            <x-breeze.input-label for="email" :value="__('Email')" />
            <x-breeze.text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-breeze.input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2" style="color: #566534;">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>


        <div>
            <x-breeze.input-label for="phone" :value="__('Nomor Telepon')" />
            <x-breeze.text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" placeholder="08123456789" />
            <x-breeze.input-error class="mt-2" :messages="$errors->get('phone')" />
            <p class="mt-1 text-xs text-gray-500">Format: 08xxxxxxxxxx atau +62xxxxxxxxxx</p>
        </div>


        <div>
            <x-breeze.input-label for="address" :value="__('Alamat')" />
            <x-breeze.textarea id="address" name="address" class="mt-1 block w-full" :value="old('address', $user->address)" rows="3" autocomplete="street-address" placeholder="Jl. Contoh No. 123, Kota"></x-breeze.textarea>
            <x-breeze.input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-breeze.input-label for="role" :value="__('Role')" />
                <div class="mt-2">
                    <x-ui.role-badge :role="$user->role->value" />
                </div>
            </div>

            @if($user->division)
                <div>
                    <x-breeze.input-label for="division" :value="__('Divisi')" />
                    <div class="mt-2">
                        <x-ui.division-badge :name="$user->division->name" />
                    </div>
                </div>
            @endif
        </div>

        <div>
            <x-breeze.input-label for="join_date" :value="__('Tanggal Bergabung')" />
            <p class="mt-2 text-sm" style="color: #334124;">{{ formatDate($user->join_date, 'd F Y') }}</p>
        </div>

        <div class="flex items-center gap-4">
            <x-breeze.primary-button style="background-color: #334124; border-color: #334124;">
                {{ __('Simpan') }}
            </x-breeze.primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>