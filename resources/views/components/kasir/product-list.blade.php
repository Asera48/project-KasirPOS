<div class="w-full lg:w-3/5 p-4 overflow-y-auto">
    <!-- Pencarian Produk -->
    <div class="mb-4">
        <input type="text" x-model="searchQuery" @input.debounce.300ms="searchProducts"
            placeholder="Cari produk berdasarkan nama atau SKU..."
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
    </div>

    <!-- Grid Produk -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <template x-for="product in filteredProducts" :key="product.id">
            <div @click="addToCart(product)"
                class="bg-white dark:bg-gray-800 rounded-lg shadow-md cursor-pointer hover:shadow-xl transition-shadow duration-200">
                <div class="p-4">
                    <img :src="`https://placehold.co/300x200/e2e8f0/333?text=${encodeURIComponent(product.name)}`"
                        :alt="product.name" class="w-full h-24 object-cover rounded-md mb-3">
                    <h4 class="font-semibold text-sm text-gray-800 dark:text-gray-200 truncate" :title="product.name"
                        x-text="product.name"></h4>
                    <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400"
                        x-text="format_rupiah(product.price)"></p>
                </div>
                <div class="w-full bg-indigo-500 text-white text-sm font-bold py-2 rounded-b-lg text-center">
                    Tambah
                </div>
            </div>
        </template>
        <template x-if="filteredProducts.length === 0">
            <p class="col-span-full text-center text-gray-500">Produk tidak ditemukan.</p>
        </template>
    </div>
</div>