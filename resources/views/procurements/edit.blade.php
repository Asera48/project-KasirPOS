<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pengadaan Barang #') }}{{ $procurement->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Menginisialisasi form dengan item yang sudah ada --}}
                    <form action="{{ route('admin.procurements.update', $procurement) }}" method="POST" x-data="procurementForm({{ json_encode($products) }}, {{ json_encode($procurement->items) }})">
                        @csrf
                        @method('PUT')
                        @include('procurements._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
