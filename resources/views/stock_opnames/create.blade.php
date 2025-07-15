<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Catat Stok Opname Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.stock-opnames.store') }}" method="POST" x-data="stockOpnameForm({{ json_encode($products) }})">
                        @csrf
                        <x-validation-errors class="mb-4" />

                        <div class="space-y-6">
                            <!-- Pilih Produk -->
                            <div>
                                <x-input-label for="product_id" :value="__('Produk')" />
                                <select name="product_id" id="product_id" x-model="selectedProductId" @change="updateSystemStock()" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Stok Sistem (Read-only) -->
                            <div>
                                <x-input-label for="system_stock" :value="__('Stok Menurut Sistem')" />
                                <p x-text="systemStock" class="mt-2 text-lg font-semibold text-gray-700 dark:text-gray-300"></p>
                            </div>

                            <!-- Stok Fisik (Input) -->
                            <div>
                                <x-input-label for="physical_stock" :value="__('Stok Fisik (Hasil Hitungan)')" />
                                <x-text-input id="physical_stock" class="block mt-1 w-full" type="number" name="physical_stock" :value="old('physical_stock')" required />
                            </div>

                            <!-- Catatan -->
                            <div>
                                <x-input-label for="note" :value="__('Catatan (Opsional)')" />
                                <textarea name="note" id="note" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">{{ old('note') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Contoh: Ditemukan 1 barang rusak, selisih karena salah input pengadaan.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('admin.stock-opnames.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">Batal</a>
                            <x-primary-button>Simpan & Sesuaikan Stok</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function stockOpnameForm(products) {
            return {
                products: products,
                selectedProductId: '',
                systemStock: 'Pilih produk untuk melihat stok',
                updateSystemStock() {
                    if (!this.selectedProductId) {
                        this.systemStock = 'Pilih produk untuk melihat stok';
                        return;
                    }
                    const selectedProduct = this.products.find(p => p.id == this.selectedProductId);
                    this.systemStock = selectedProduct ? selectedProduct.stock : 'Produk tidak ditemukan';
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
