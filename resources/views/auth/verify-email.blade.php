<x-guest-layout>
    <div>
        <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900 dark:text-white">Verifikasi Email Anda</h2>
        <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">
            Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan?
        </p>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkan yang lain.
        </p>
    </div>

    <div class="mt-8">
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 p-4 bg-green-50 dark:bg-green-900/50 rounded-lg">
                {{ __('Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-primary-button>
                    {{ __('Kirim Ulang Email Verifikasi') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    {{ __('Keluar') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
