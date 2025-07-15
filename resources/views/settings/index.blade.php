<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengaturan Aplikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <x-notification type="success" :message="session('success')" />
                    @endif
                    
                    <x-validation-errors class="mb-4" />

                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Pengaturan Umum</h3>
                            <div>
                                <x-input-label for="app_name" value="Nama Aplikasi" />
                                <x-text-input id="app_name" name="app_name" type="text" class="mt-1 block w-full" :value="old('app_name', setting('app_name', config('app.name')))" required />
                            </div>

                            <div>
                                <x-input-label for="app_logo" value="Logo Aplikasi (Opsional)" />
                                <input id="app_logo" name="app_logo" type="file" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300"/>
                                <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah logo.</p>
                                @if(setting('app_logo'))
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-500">Logo saat ini:</p>
                                        <img src="{{ asset('storage/' . setting('app_logo')) }}" alt="Current Logo" class="h-16 w-auto rounded-md bg-gray-200 p-1">
                                        <label class="mt-2 flex items-center">
                                            <input type="checkbox" name="delete_logo" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Hapus logo saat ini</span>
                                        </label>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <x-input-label for="store_address" value="Alamat Toko" />
                                <textarea id="store_address" name="store_address" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm">{{ old('store_address', setting('store_address', '')) }}</textarea>
                            </div>

                            <div>
                                <x-input-label for="store_phone" value="Telepon Toko" />
                                <x-text-input id="store_phone" name="store_phone" type="text" class="mt-1 block w-full" :value="old('store_phone', setting('store_phone', ''))" />
                            </div>

                            <h3 class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 pt-4">Pengaturan Transaksi</h3>
                            <div>
                                <x-input-label for="tax_rate" value="Tarif Pajak (%)" />
                                <x-text-input id="tax_rate" name="tax_rate" type="number" class="mt-1 block w-full" :value="old('tax_rate', setting('tax_rate', 11))" required step="any" />
                            </div>

                            {{-- pengaturan poin member --}}
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 pt-4">Pengaturan Poin Member</h3>
                            <div>
                                <x-input-label for="points_per_amount" value="Perolehan Poin" />
                                <div class="mt-1 flex items-center gap-2">
                                    <span>Dapatkan 1 Poin setiap kelipatan belanja</span>
                                    <x-text-input id="points_per_amount" name="points_per_amount" type="number" class="w-48" :value="old('points_per_amount', setting('points_per_amount', 10000))" required />
                                </div>
                            </div>
                             <div>
                                <x-input-label for="point_value" value="Nilai Tukar Poin" />
                                 <div class="mt-1 flex items-center gap-2">
                                    <span>1 Poin bernilai</span>
                                    <x-text-input id="point_value" name="point_value" type="number" class="w-48" :value="old('point_value', setting('point_value', 100))" required />
                                    <span>Rupiah</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <x-primary-button>Simpan Pengaturan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
