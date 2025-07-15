<x-validation-errors class="mb-4" />

<!-- Nama Kategori -->
<div>
    <x-input-label for="name" :value="__('Nama Kategori')" />
    <x-text-input 
        id="name" 
        class="block mt-1 w-full" 
        type="text" 
        name="name" 
        :value="old('name', $category->name ?? '')" 
        required 
        autofocus 
    />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">
        Batal
    </a>

    <x-primary-button>
        {{ isset($category) ? 'Perbarui' : 'Simpan' }}
    </x-primary-button>
</div>