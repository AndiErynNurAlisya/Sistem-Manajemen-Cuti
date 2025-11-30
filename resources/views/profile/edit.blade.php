
<x-app-layout>
    <x-slot name="pageTitle">Edit Profil</x-slot>
    <x-slot name="pageDescription">Perbarui informasi profil akun Anda</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg" style="border-left: 4px solid #566534;">
                <div class="max-w-xl">
                    @include('profile.partials.update-avatar-form')
                </div>
            </div>

            @if(in_array(auth()->user()->role->value, ['employee', 'leader']))
                <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg" style="border-left: 4px solid #566534;">
                    <div class="max-w-2xl">
                        @include('profile.partials.quota-display')
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg" style="border-left: 4px solid #566534;">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg" style="border-left: 4px solid #566534;">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg" style="border-left: 4px solid #ef4444;">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>