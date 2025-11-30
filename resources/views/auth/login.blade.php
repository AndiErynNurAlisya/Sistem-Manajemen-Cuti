<x-guest-layout>
    <!-- Session Status -->
    <x-breeze.auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-breeze.input-label for="email" :value="__('Email')" 
                class="text-sm font-medium mb-2" style="color: #334124;" />
            
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5" style="color: #566534;" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                    </svg>
                </div>
                <x-breeze.text-input id="email" 
                    class="input-field block w-full pl-12 pr-4 py-3 rounded-xl text-gray-700" 
                    type="email" name="email" :value="old('email')" 
                    required autofocus autocomplete="username"
                    placeholder="Enter your email" />
            </div>
            
            <x-breeze.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-breeze.input-label for="password" :value="__('Password')" 
                class="text-sm font-medium mb-2" style="color: #334124;" />

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5" style="color: #566534;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <x-breeze.text-input id="password" 
                    class="input-field block w-full pl-12 pr-4 py-3 rounded-xl text-gray-700"
                    type="password" name="password" 
                    required autocomplete="current-password"
                    placeholder="Enter your password" />
            </div>

            <x-breeze.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 shadow-sm"
                    style="color: #566534; border-color: #b5b89b;"
                    name="remember">
                <span class="ms-2 text-sm" style="color: #334124;">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Login Button -->
        <div class="pt-2">
            <button type="submit" 
                class="login-btn w-full py-3 px-4 rounded-xl text-white font-semibold shadow-lg">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>