<x-guest-layout>
    <div>
        <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900 dark:text-white">Area Aman</h2>
        <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">
            Ini adalah area aman aplikasi. Mohon konfirmasi password Anda sebelum melanjutkan.
        </p>
    </div>

    <div class="mt-8">
        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <div class="mt-2 relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <x-text-input id="password" class="block w-full ps-10" type="password" name="password" required
                        autocomplete="current-password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Tombol Konfirmasi -->
            <div>
                <x-primary-button class="w-full flex justify-center">
                    {{ __('Konfirmasi') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>