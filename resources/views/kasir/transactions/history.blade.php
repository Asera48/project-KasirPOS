<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Transaksi Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Form Filter Tanggal -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tampilkan Transaksi untuk Tanggal</h3>
                    <form action="{{ route('kasir.transactions.history') }}" method="GET" class="mt-4">
                        <div class="flex items-center gap-4">
                            <div class="flex-grow">
                                <x-text-input id="filter_date" type="date" name="filter_date" class="block w-full" :value="$filterDate" />
                            </div>
                            <div>
                                <x-primary-button>Tampilkan</x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tampilan Timeline Transaksi -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <x-notification type="success" :message="session('success')" />
                    @endif

                    <div class="flow-root">
                        @forelse ($transactions as $transaction)
                            <div class="relative flex items-start space-x-4 py-6">
                                <div class="flex-shrink-0">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-500 ring-8 ring-white dark:ring-gray-800">
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 21Z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                Transaksi <a href="{{ route('kasir.transactions.show', $transaction) }}" class="text-indigo-600 hover:underline">#{{ $transaction->invoice_number ?? $transaction->id }}</a>
                                            </p>
                                            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                                                Untuk: {{ $transaction->member->name ?? 'Pelanggan Umum' }}
                                            </p>
                                        </div>
                                        <div class="text-right text-sm text-gray-500 dark:text-gray-400">
                                            <time datetime="{{ $transaction->transaction_date->toIso8601String() }}">{{ $transaction->transaction_date->format('H:i') }}</time>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                        <p>
                                            Total Belanja: <span class="font-semibold">{{ format_rupiah($transaction->total) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">Tidak Ada Transaksi</h3>
                                <p class="mt-1 text-sm text-gray-500">Anda belum melakukan transaksi pada tanggal yang dipilih.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
