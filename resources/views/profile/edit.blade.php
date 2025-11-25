@extends('layouts.app'){{-- Asumsi menggunakan layouts.app. Sesuaikan jika layout utama Anda berbeda. --}}@section('content')<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8"><header><h2 class="text-3xl font-bold text-gray-900">Edit Profil</h2><p class="mt-1 text-sm text-gray-600">Perbarui informasi akun, alamat email, dan kata sandi Anda.</p></header><div class="mt-10 space-y-10">
    
    <!-- Bagian 1: Update Informasi Profil -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <!-- Bagian 2: Update Kata Sandi -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <!-- Bagian 3: Hapus Akun -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
</div>@endsection