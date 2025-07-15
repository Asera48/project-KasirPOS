<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Pengadaan #') }}{{ $procurement->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Pengadaan</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                <strong>Tanggal:</strong> {{ $procurement->procurement_date->format('d F Y') }}<br>
                                <strong>Supplier:</strong> {{ $procurement->supplier->name ?? 'N/A' }}<br>
                                <strong>No. Referensi:</strong> {{ $procurement->reference_number ?? '-' }}<br>
                                <strong>Dicatat Oleh:</strong> {{ $procurement->user->name ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Total Biaya</h3>
                            <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ format_rupiah($procurement->total_cost) }}</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 dark:text-white border-t pt-4 mt-4 border-gray-200 dark:border-gray-700">Rincian Barang</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Produk</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Harga Pokok</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($procurement->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">{{ format_rupiah($item->cost_price) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">{{ format_rupiah($item->quantity * $item->cost_price) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 text-right">
                        <a href="{{ route('admin.procurements.index') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">Kembali ke Daftar Pengadaan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
