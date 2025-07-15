<x-validation-errors class="mb-4" />

<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Nama Lengkap')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $member->name ?? '')" required autofocus />
    </div>
    <div>
        <x-input-label for="phone" :value="__('Nomor Telepon (Opsional)')" />
        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $member->phone ?? '')" />
    </div>
    <div>
        <x-input-label for="points" :value="__('Poin (Opsional)')" />
        <x-text-input id="points" class="block mt-1 w-full" type="number" name="points" :value="old('points', $member->points ?? 0)" />
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    @php
        $cancelRoute = Auth::user()->isAdmin() ? route('admin.members.index') : route('kasir.members.index');
    @endphp
    <a href="{{ $cancelRoute }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">
        Batal
    </a>

    <x-primary-button>{{ isset($member) ? 'Perbarui' : 'Simpan' }}</x-primary-button>
</div>
