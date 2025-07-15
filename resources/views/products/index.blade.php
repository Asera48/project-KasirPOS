<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="productManager({{ json_encode($products->items()) }})">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <a href="{{ route('admin.products.create') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            <span>Tambah Produk</span>
                        </a>
                        <div class="w-full sm:w-auto sm:max-w-xs relative">
                            <input type="text" x-model="searchQuery" @input.debounce.300ms="searchProducts()" placeholder="Cari produk..." class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <div x-show="loading" class="absolute right-3 top-1/2 -translate-y-1/2" style="display: none;">
                                <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <x-notification type="success" :message="session('success')" />
                    @endif

                    @if($errors->has('error'))
                        <x-notification type="error" :message="$errors->first('error')" />
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><x-sortable-link column="name" label="Nama Produk" /></th>
                                    {{-- PERBAIKAN: Menambahkan kolom Barcode --}}
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><x-sortable-link column="barcode" label="Barcode" /></th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><x-sortable-link column="category_name" label="Kategori" /></th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><x-sortable-link column="price" label="Harga Jual" /></th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><x-sortable-link column="cost_price" label="Harga Pokok" /></th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><x-sortable-link column="stock" label="Stok" /></th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-for="product in products" :key="product.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white" x-text="product.name"></td>
                                        {{-- PERBAIKAN: Menampilkan data Barcode --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="product.barcode || '-'"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="product.category ? product.category.name : 'N/A'"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="format_rupiah(product.price)"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="format_rupiah(product.cost_price)"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="product.stock"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                            <a :href="'{{ route("admin.products.edit", ["product" => "PLACEHOLDER"]) }}'.replace('PLACEHOLDER', product.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Edit</a>
                                            <form :action="'{{ route("admin.products.destroy", ["product" => "PLACEHOLDER"]) }}'.replace('PLACEHOLDER', product.id)" method="POST" class="inline-block ml-4" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="products.length === 0 && !loading">
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada produk yang ditemukan.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4" x-show="!searchQuery">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function format_rupiah(number) {
            if (isNaN(parseFloat(number))) return 'Rp0';
            return 'Rp' + new Intl.NumberFormat('id-ID').format(number);
        }

        function productManager(initialProducts) {
            return {
                products: initialProducts,
                searchQuery: '',
                loading: false,
                searchProducts() {
                    if (this.searchQuery.trim() === '') {
                        window.location.href = '{{ route("admin.products.index") }}';
                        return;
                    }

                    this.loading = true;
                    fetch(`{{ route('api.products.search') }}?q=${this.searchQuery}`)
                        .then(response => response.json())
                        .then(data => {
                            this.products = data;
                        })
                        .finally(() => this.loading = false);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
