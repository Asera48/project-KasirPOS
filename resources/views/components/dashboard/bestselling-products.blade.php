@props(['products' => []])

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg h-full">
    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Produk Terlaris</h3>
    <div class="mt-4 space-y-4">
        @forelse ($products as $item)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-full">
                        <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.658-.463 1.243-1.117 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.117 1.007Z" /></svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                        <p class="text-sm text-gray-500">Terjual {{ $item->total_sold }} unit</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-center text-gray-500 mt-4">Belum ada data penjualan.</p>
        @endforelse
    </div>
</div>
