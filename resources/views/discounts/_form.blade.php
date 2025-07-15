<x-validation-errors class="mb-4" />

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="product_id" :value="__('Produk')" />
        <select name="product_id" id="product_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
            <option value="">Pilih Produk</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" @selected(old('product_id', $discount->product_id ?? '') == $product->id)>
                    {{ $product->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="type" :value="__('Tipe Diskon')" />
        <select name="type" id="type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
            <option value="percentage" @selected(old('type', $discount->type ?? '') == 'percentage')>Persentase (%)</option>
            <option value="fixed" @selected(old('type', $discount->type ?? '') == 'fixed')>Potongan Tetap (Rp)</option>
        </select>
    </div>

    <div>
        <x-input-label for="value" :value="__('Nilai Diskon')" />
        <x-text-input id="value" class="block mt-1 w-full" type="number" name="value" :value="old('value', $discount->value ?? '')" required />
        <p class="mt-1 text-xs text-gray-500">Contoh: Isi 10 untuk 10% atau 10000 untuk potongan Rp 10.000.</p>
    </div>

    <div>
        <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
        <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', isset($discount) ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') : '')" required />
    </div>

    <div>
        <x-input-label for="end_date" :value="__('Tanggal Selesai')" />
        <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', isset($discount) ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d') : '')" required />
    </div>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.discounts.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">
        Batal
    </a>

    <x-primary-button>
        {{ isset($discount) ? 'Perbarui' : 'Simpan' }}
    </x-primary-button>
</div>
