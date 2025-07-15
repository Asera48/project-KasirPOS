<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Transaksi ') }}{{ $transaction->invoice_number ?? "#".$transaction->id }}
            </h2>
            {{-- Tombol Cetak Struk --}}
            <div>
                @php
                    $printRoute = Auth::user()->isAdmin() ? 'admin.transactions.print' : 'kasir.transactions.print';
                @endphp
                <a href="{{ route($printRoute, $transaction) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.32 0c.662 0 1.18-.568 1.12-1.227L17.66 5.094A2.25 2.25 0 0 0 15.435 3h-6.87a2.25 2.25 0 0 0-2.225 2.094L6.34 16.773M12 12.75h.008v.008H12v-.008Z" /></svg>
                    Cetak Struk
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <x-notification type="success" :message="session('success')" />
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Transaksi</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                <strong>Tanggal:</strong> {{ $transaction->transaction_date ? $transaction->transaction_date->format('d F Y, H:i') : 'N/A' }}<br>
                                <strong>Kasir:</strong> {{ $transaction->user->name ?? 'N/A' }}<br>
                                <strong>Pelanggan:</strong> {{ $transaction->member->name ?? 'Umum' }}<br>
                                <strong>Metode Bayar:</strong> <span class="capitalize">{{ $transaction->payment_method }}</span>
                            </p>
                        </div>
                        <div class="text-left md:text-right">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Rincian Pembayaran</h3>
                            {{-- PERBAIKAN: Menambahkan baris Subtotal dan Pajak --}}
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                <strong>Subtotal:</strong> {{ format_rupiah($transaction->subtotal) }}<br>
                                <strong>Pajak:</strong> {{ format_rupiah($transaction->tax_amount) }}<br>
                                <strong>Total Belanja:</strong> {{ format_rupiah($transaction->total) }}<br>
                                <strong>Dibayar:</strong> {{ format_rupiah($transaction->paid) }}<br>
                                <strong>Kembalian:</strong> {{ format_rupiah($transaction->change) }}
                            </p>
                        </div>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 dark:text-white border-t pt-4 mt-4 border-gray-200 dark:border-gray-700">Rincian Barang</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Produk</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jml</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Harga Satuan</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Subtotal</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Laba</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @php $totalProfit = 0; @endphp
                                @foreach($transaction->items as $item)
                                    @php
                                        $finalPrice = $item->price - $item->discount_amount;
                                        $profitPerItem = ($finalPrice - $item->cost_price) * $item->quantity;
                                        $totalProfit += $profitPerItem;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                                            @if($item->discount_amount > 0)
                                                <div class="text-xs text-red-500">
                                                    Diskon
                                                    @if($item->discount_type == 'percentage')
                                                        {{ $item->discount_value }}%
                                                    @endif
                                                    (-{{ format_rupiah($item->discount_amount) }})
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">{{ format_rupiah($item->price) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">{{ format_rupiah($item->subtotal) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-green-600">{{ format_rupiah($profitPerItem) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right font-bold text-gray-700 dark:text-gray-200">Total Laba Transaksi</td>
                                    <td class="px-6 py-3 text-right font-bold text-green-600 dark:text-green-400">{{ format_rupiah($totalProfit) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-6 text-right">
                        <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
