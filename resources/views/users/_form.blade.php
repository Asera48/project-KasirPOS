<x-validation-errors class="mb-4" />

<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Nama Lengkap')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name ?? '')" required autofocus />
    </div>
    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email ?? '')" required />
    </div>
    <div>
        <x-input-label for="role" :value="__('Peran')" />
        <select name="role" id="role" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
            <option value="kasir" @selected(old('role', $user->role ?? '') == 'kasir')>Kasir</option>
            <option value="admin" @selected(old('role', $user->role ?? '') == 'admin')>Admin</option>
        </select>
    </div>
    <div>
        <x-input-label for="password" :value="__('Password')" />
        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
        @if (isset($user))
            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
        @endif
    </div>
    <div>
        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">Batal</a>
    <x-primary-button>{{ isset($user) ? 'Perbarui' : 'Simpan' }}</x-primary-button>
</div>
