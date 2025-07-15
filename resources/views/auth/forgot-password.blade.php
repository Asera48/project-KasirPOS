<x-guest-layout>
    <div>
        <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900 dark:text-white">Lupa Password?</h2>
        <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">
            Tidak masalah. Beri tahu kami alamat email Anda dan kami akan mengirimkan link untuk mengatur ulang password.
        </p>
    </div>

    <div class="mt-8">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <div class="mt-2 relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" /><path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" /></svg>
                    </div>
                    <x-text-input id="email" class="block w-full ps-10" type="email" name="email" :value="old('email')" required autofocus />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Tombol Kirim Link -->
            <div>
                <x-primary-button class="w-full flex justify-center">
                    {{ __('Kirim Link Reset Password') }}
                </x-primary-button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Kembali ke Login</a>
            </div>
        </form>
    </div>
</x-guest-layout>
