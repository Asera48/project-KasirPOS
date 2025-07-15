<x-validation-errors class="mb-4" />

{{-- Informasi Umum --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <x-input-label for="procurement_date" :value="__('Tanggal Pengadaan')" />
        <x-text-input id="procurement_date" class="block mt-1 w-full" type="date" name="procurement_date" :value="old('procurement_date', isset($procurement) ? $procurement->procurement_date->format('Y-m-d') : now()->format('Y-m-d'))" required />
    </div>
    <div>
        <x-input-label for="supplier_id" :value="__('Supplier')" />
        <select name="supplier_id" id="supplier_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
            <option value="">Pilih Supplier</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $procurement->supplier_id ?? '') == $supplier->id)>{{ $supplier->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <x-input-label for="reference_number" :value="__('Nomor Referensi (Opsional)')" />
        <x-text-input id="reference_number" class="block mt-1 w-full" type="text" name="reference_number" :value="old('reference_number', $procurement->reference_number ?? '')" />
    </div>
</div>

{{-- Rincian Barang --}}
<h3 class="text-lg font-medium text-gray-900 dark:text-white border-t pt-4 mt-4 border-gray-200 dark:border-gray-700">Barang yang Dibeli</h3>
<div class="mt-4 space-y-4">
    <template x-for="(item, index) in items" :key="index">
        <div class="flex items-end gap-4 p-4 border rounded-lg dark:border-gray-700">
            <div class="flex-grow">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Produk</label>
                <select 
                    :name="`items[${index}][product_id]`" 
                    x-model="item.product_id"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" 
                    required
                >
                    <option value="">Pilih Produk</option>
                    <template x-for="product in products" :key="product.id">
                        {{-- PERBAIKAN: Menggunakan :selected untuk memastikan pilihan yang benar --}}
                        <option :value="product.id" :selected="product.id == item.product_id" x-text="product.name"></option>
                    </template>
                </select>
            </div>
            <div class="w-24">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah</label>
                <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" min="1" required>
            </div>
            <div class="w-36">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Pokok</label>
                <input type="number" :name="`items[${index}][cost_price]`" x-model="item.cost_price" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" min="0" required>
            </div>
            <button type="button" @click="removeItem(index)" class="bg-red-500 text-white rounded-md p-2 hover:bg-red-600">&times;</button>
        </div>
    </template>
</div>

<button type="button" @click="addItem" class="mt-4 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium py-2 px-4 rounded-lg">Tambah Barang</button>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('admin.procurements.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">Batal</a>
    <x-primary-button>
        {{ isset($procurement) ? 'Perbarui Pengadaan' : 'Simpan Pengadaan' }}
    </x-primary-button>
</div>

@push('scripts')
<script>
    function procurementForm(products, initialItems = []) {
        return {
            products: products,
            items: initialItems.length > 0 ? initialItems.map(item => ({
                product_id: item.product_id,
                quantity: item.quantity,
                cost_price: item.cost_price
            })) : [
                { product_id: '', quantity: 1, cost_price: '' }
            ],
            addItem() {
                this.items.push({ product_id: '', quantity: 1, cost_price: '' });
            },
            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            }
        }
    }
</script>
@endpush
