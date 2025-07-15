<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Impor Data Pengadaan dari Excel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Petunjuk penggunaan  --}}
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-gray-700/50 border border-blue-200 dark:border-gray-600 rounded-lg">
                        <h4 class="font-bold text-blue-800 dark:text-blue-300">Petunjuk Penggunaan:</h4>
                        <ol class="list-decimal list-inside mt-2 text-sm text-gray-700 dark:text-gray-400 space-y-1">
                            <li>Unduh template Excel. Template ini sudah berisi daftar semua produk Anda.</li>
                            <li>Buka file template. **Hanya isi** kolom `jumlah` dan `harga_pokok_satuan` untuk produk yang Anda beli.</li>
                            <li>Biarkan baris produk yang tidak dibeli tetap kosong di kolom `jumlah` dan `harga_pokok_satuan`.</li>
                            <li>**Jangan mengubah** isi kolom `kategori`, `nama_produk`, dan `barcode_sku`.</li>
                            <li>Pilih tanggal pengadaan dan supplier, lalu unggah file Excel yang sudah diisi.</li>
                        </ol>
                        <div class="mt-4">
                            <a href="{{ route('admin.procurements.template.download') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Unduh Template Excel
                            </a>
                        </div>
                    </div>

                    {{-- Menampilkan Error Validasi dari Excel --}}
                    @if ($errors->has('import_errors'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                            <strong class="font-bold">Gagal mengimpor!</strong>
                            <p class="block sm:inline">Ada beberapa kesalahan pada file Excel Anda:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->get('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- <x-validation-errors class="mb-4" /> -->

                    <form action="{{ route('admin.procurements.import.handle') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="procurement_date" :value="__('Tanggal Pengadaan')" />
                                    <x-text-input id="procurement_date" class="block mt-1 w-full" type="date" name="procurement_date" :value="old('procurement_date', now()->format('Y-m-d'))" required />
                                </div>
                                <div>
                                    <x-input-label for="supplier_id" :value="__('Supplier')" />
                                    <select name="supplier_id" id="supplier_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">Pilih Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <x-input-label for="reference_number" :value="__('Nomor Referensi (Opsional)')" />
                                <x-text-input id="reference_number" class="block mt-1 w-full" type="text" name="reference_number" :value="old('reference_number')" />
                            </div>
                            <div>
                                <x-input-label for="import_file" :value="__('File Excel (.xlsx, .xls, .csv)')" />
                                <input id="import_file" name="import_file" type="file" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('admin.procurements.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">Batal</a>
                            <x-primary-button>Proses Impor</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
