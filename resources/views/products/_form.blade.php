<x-validation-errors class="mb-6" />

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Nama Produk -->
    <div>
        <x-input-label for="name" :value="__('Nama Produk')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Kategori -->
    <div>
        <x-input-label for="category_id" :value="__('Kategori')" />
        <select name="category_id" id="category_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
            <option value="">Pilih Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>

    <!-- Harga Jual -->
    <div>
        <x-input-label for="price" :value="__('Harga Jual (Rp)')" />
        <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price', $product->price ?? '')" required />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <!-- Harga Pokok -->
    <div>
        <x-input-label for="cost_price" :value="__('Harga Pokok (Rp)')" />
        <x-text-input id="cost_price" class="block mt-1 w-full" type="number" name="cost_price" :value="old('cost_price', $product->cost_price ?? '')" />
        <x-input-error :messages="$errors->get('cost_price')" class="mt-2" />
    </div>

    <!-- Stok -->
    <div>
        <x-input-label for="stock" :value="__('Stok')" />
        <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock" :value="old('stock', $product->stock ?? '')" required />
        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
    </div>

    <!-- Barcode -->
    <div>
        <x-input-label for="barcode" :value="__('Barcode (SKU)')" />
        <x-text-input id="barcode" class="block mt-1 w-full" type="text" name="barcode" :value="old('barcode', $product->barcode ?? '')" />
        <x-input-error :messages="$errors->get('barcode')" class="mt-2" />
    </div>
</div>

<!-- Deskripsi -->
<div class="mt-6">
    <x-input-label for="description" :value="__('Deskripsi')" />
    <textarea name="description" id="description" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $product->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<!-- Gambar Produk -->
<div class="mt-6">
    <x-input-label for="image" :value="__('Gambar Produk')" />
    <input type="file" name="image" id="image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300">
    @if(isset($product) && $product->image)
        <div class="mt-4">
            <p class="text-sm text-gray-500">Gambar saat ini:</p>
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="mt-2 w-32 h-32 object-cover rounded-md">
        </div>
    @endif
    <x-input-error :messages="$errors->get('image')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">
        Batal
    </a>
    <x-primary-button>
        {{ isset($product) ? 'Perbarui Produk' : 'Simpan Produk' }}
    </x-primary-button>
</div>
