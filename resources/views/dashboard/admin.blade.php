<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dasbor Admin') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-dashboard.stats-card title="Total Produk" :value="$productsCount ?? 0" color="indigo">
                    <x-slot name="icon"><svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg></x-slot>
                </x-dashboard.stats-card>
                <x-dashboard.stats-card title="Total Transaksi" :value="$transactionsCount ?? 0" color="green">
                    <x-slot name="icon"><svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 21Z" />
                        </svg></x-slot>
                </x-dashboard.stats-card>
                <x-dashboard.stats-card title="Total Pengguna" :value="$usersCount ?? 0" color="purple">
                    <x-slot name="icon"><svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-2.253-9.527 9.527 0 0 0-4.121-2.253M15 19.128v-3.857M15 19.128c-1.56 0-3.078-.756-4.121-2.253M15 19.128V15M10.5 17.25h.008v.008h-.008v-.008Zm0 0c0 .065.008.128.023.191a4.5 4.5 0 0 1-1.135 2.472M8.25 15a4.5 4.5 0 0 1 4.5-4.5m0 0a4.5 4.5 0 0 1 4.5 4.5M3 15a4.5 4.5 0 0 1 4.5-4.5m0 0a4.5 4.5 0 0 1 4.5 4.5" />
                        </svg></x-slot>
                </x-dashboard.stats-card>
            </div>

            <!-- Grafik dan Panel Baru -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Grafik Penjualan -->
                <div class="lg:col-span-2">
                    <x-dashboard.sales-chart />
                </div>

                {{-- PERBAIKAN: Menggunakan flexbox untuk menata panel kanan --}}
                <div class="flex flex-col space-y-6">
                    <x-dashboard.bestselling-products :products="$bestsellingProducts" />
                    <x-dashboard.low-stock-products :products="$lowStockProducts" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>