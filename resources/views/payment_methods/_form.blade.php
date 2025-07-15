<x-validation-errors class="mb-4" />

<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Nama Tampilan')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $paymentMethod->name ?? '')" required autofocus />
        <p class="mt-1 text-xs text-gray-500">Contoh: "Tunai", "QRIS Gopay", "Kartu Debit BCA"</p>
    </div>

    <div>
        <x-input-label for="code" :value="__('Kode Unik')" />
        <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code', $paymentMethod->code ?? '')" required />
        <p class="mt-1 text-xs text-gray-500">Gunakan huruf kecil tanpa spasi. Contoh: "cash", "qris_gopay", "card_bca"</p>
    </div>
    
    <div>
        <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
        <textarea name="description" id="description" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">{{ old('description', $paymentMethod->description ?? '') }}</textarea>
    </div>

    <div>
        <x-input-label for="qr_code_image" :value="__('Gambar QR Code (Opsional)')" />
        <input id="qr_code_image" name="qr_code_image" type="file" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300"/>
        <p class="mt-1 text-xs text-gray-500">Hanya diperlukan untuk metode pembayaran berbasis QR.</p>
        
        @if(isset($paymentMethod) && $paymentMethod->qr_code_image)
            <div class="mt-4">
                <p class="text-sm text-gray-500">Gambar saat ini:</p>
                <img src="{{ asset('storage/' . $paymentMethod->qr_code_image) }}" alt="QR Code" class="mt-2 w-32 h-32 object-contain rounded-md bg-gray-200 p-1">
                <label class="mt-2 flex items-center">
                    <input type="checkbox" name="delete_qr_code" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Hapus gambar QR saat ini</span>
                </label>
            </div>
        @endif
    </div>

    <div>
        <label for="is_active" class="flex items-center">
            <input id="is_active" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" @checked(old('is_active', $paymentMethod->is_active ?? true))>
            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Aktifkan metode pembayaran ini') }}</span>
        </label>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('admin.payment-methods.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">
        Batal
    </a>

    <x-primary-button>
        {{ isset($paymentMethod) ? 'Perbarui' : 'Simpan' }}
    </x-primary-button>
</div>
