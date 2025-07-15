<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Form Filter Tanggal -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">
                            Filter Laporan
                        </h3>
                        {{-- PERUBAHAN: Menambahkan tombol Cetak & Ekspor --}}
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.reports.sales.print', request()->query()) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Cetak</a>
                            <a href="{{ route('admin.reports.sales.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">Ekspor Excel</a>
                        </div>
                    </div>
                    <form action="{{ route('admin.reports.sales') }}" method="GET">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 items-end">
                            <div>
                                <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                                <x-text-input id="start_date" type="date" name="start_date" class="block mt-1 w-full" :value="$startDate" />
                            </div>
                            <div>
                                <x-input-label for="end_date" :value="__('Tanggal Selesai')" />
                                <x-text-input id="end_date" type="date" name="end_date" class="block mt-1 w-full" :value="$endDate" />
                            </div>
                            <div>
                                <x-primary-button class="w-full sm:w-auto justify-center">
                                    Terapkan Filter
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ringkasan Laporan -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h4 class="text-gray-500 dark:text-gray-400">Total Transaksi</h4>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $summary->total_transactions ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h4 class="text-gray-500 dark:text-gray-400">Total Pendapatan</h4>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ format_rupiah($summary->total_revenue ?? 0) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h4 class="text-gray-500 dark:text-gray-400">Total Laba</h4>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ format_rupiah($summary->total_profit ?? 0) }}</p>
                </div>
            </div>

            <!-- Tabel Rincian Transaksi -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Rincian Transaksi ({{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Invoice Transaksi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Kasir</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                            <a href="{{ route('admin.transactions.show', $transaction) }}">#{{ $transaction->invoice_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $transaction->transaction_date->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $transaction->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">{{ format_rupiah($transaction->total) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada transaksi pada rentang tanggal ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
