<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <div
            class="max-w-md w-full space-y-6 p-6 bg-white rounded-2xl shadow-2xl backdrop-blur-sm border border-gray-100">

            <!-- Header -->
            <div class="text-center">
                <div class="pohonlogo">
                    {{-- <a href="/">
                        <img src="{{ asset('storage/pohonLogo.png') }}" alt="Logo" class="mx-auto w-20 h-auto" />
                    </a> --}}
                </div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-1">Selamat Datang</h2>
                <p class="text-sm text-gray-600">Silakan masuk ke akun Anda</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-3 text-xs" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div class="space-y-1">
                    <x-input-label for="email" :value="__('Email')" class="text-xs font-medium text-gray-700" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <x-text-input id="email"
                            class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm"
                            type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                            placeholder="nama@email.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <x-input-label for="password" :value="__('Password')" class="text-xs font-medium text-gray-700" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <x-text-input id="password"
                            class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-sm"
                            type="password" name="password" required autocomplete="current-password"
                            placeholder="Masukkan password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="h-3.5 w-3.5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition duration-200"
                        name="remember">
                    <label for="remember_me" class="ml-2 block text-xs text-gray-700 cursor-pointer">
                        {{ __('Ingat saya') }}
                    </label>
                </div>

                <!-- Submit Button and Forgot Password -->
                <div class="space-y-3">
                    <x-primary-button
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-blue-600 hover:from-purple-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Masuk') }}
                    </x-primary-button>

                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-500 transition duration-200"
                                href="{{ route('password.request') }}">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('Lupa password?') }}
                            </a>
                        </div>
                    @endif
                </div>
            </form>

            <!-- Divider -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-2 bg-white text-gray-400">atau</span>
                    </div>
                </div>
            </div>
            <!-- Register Link -->
            <div class="text-center mt-4">
                <p class="text-xs text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}"
                        class="font-medium text-blue-600 hover:text-blue-500 transition duration-200">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
