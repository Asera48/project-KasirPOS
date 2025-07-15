@props(['products' => []])

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg h-full">
    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Stok Akan Habis</h3>
    <div class="mt-4 space-y-4">
        @forelse ($products as $product)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-full">
                        <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $product->name }}</p>
                        <p class="text-sm text-red-500">Sisa {{ $product->stock }} unit</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-center text-gray-500 mt-4">Tidak ada data produk.</p>
        @endforelse
    </div>
</div>
