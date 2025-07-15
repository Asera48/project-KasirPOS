<x-validation-errors class="mb-4" />

<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Nama Supplier')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $supplier->name ?? '')" required autofocus />
    </div>
    <div>
        <x-input-label for="contact_person" :value="__('Kontak Person (Opsional)')" />
        <x-text-input id="contact_person" class="block mt-1 w-full" type="text" name="contact_person" :value="old('contact_person', $supplier->contact_person ?? '')" />
    </div>
    <div>
        <x-input-label for="phone" :value="__('Nomor Telepon (Opsional)')" />
        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $supplier->phone ?? '')" />
    </div>
    <div>
        <x-input-label for="address" :value="__('Alamat (Opsional)')" />
        <textarea name="address" id="address" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">{{ old('address', $supplier->address ?? '') }}</textarea>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('admin.suppliers.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">
        Batal
    </a>
    <x-primary-button>
        {{ isset($supplier) ? 'Perbarui' : 'Simpan' }}
    </x-primary-button>
</div>
