<aside
    class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-white dark:bg-gray-900 lg:translate-x-0 lg:static lg:inset-0"
    :class="{'translate-x-0 ease-out': sidebarOpen, '-translate-x-full ease-in': !sidebarOpen}">
    <!-- App Logo -->
    <div class="flex items-center justify-center h-20 px-6 bg-gray-100 dark:bg-gray-950">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            @if(setting('app_logo'))
            <img src="{{ asset('storage/' . setting('app_logo')) }}" alt="Logo" class="block h-9 w-auto">
            @else
            <x-application-logo class="block w-auto h-9 fill-current text-gray-800 dark:text-gray-200" />
            @endif
            <span
                class="text-2xl font-bold text-gray-800 dark:text-white">{{ setting('app_name', config('app.name')) }}</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="px-4 py-6">
        {{-- Menu Umum untuk semua role --}}
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
            </svg>
            <span>Dashboard</span>
        </x-nav-link>

        {{-- =============================================== --}}
        {{-- MENU KHUSUS ADMIN --}}
        {{-- =============================================== --}}
        @if (Auth::user()->isAdmin())

        <h3 class="px-3 mt-6 mb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">Laporan</h3>
        <x-nav-link :href="route('admin.reports.sales')" :active="request()->routeIs('admin.reports.sales')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h12A2.25 2.25 0 0 0 20.25 14.25V3.75M3.75 21h16.5M3.75 3.75h16.5M5.625 3.75v11.25m12.75-11.25v11.25m0 0H5.625" />
            </svg>
            <span>Laporan Penjualan</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.reports.stock')" :active="request()->routeIs('admin.reports.stock')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 3.75h.625a2.25 2.25 0 0 1 2.25 2.25v.375A2.25 2.25 0 0 1 15.75 8.25v.375a2.25 2.25 0 0 1-2.25 2.25h-.625a2.25 2.25 0 0 1-2.25-2.25v-.375A2.25 2.25 0 0 1 8.25 6.108v-.375a2.25 2.25 0 0 1 2.25-2.25Z" />
            </svg>
            <span>Laporan Stok</span>

        </x-nav-link>
        <h3 class="px-3 mt-6 mb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">Manajemen Produk</h3>
        <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
            <span>Produk</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
            </svg>
            <span>Kategori</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.discounts.index')" :active="request()->routeIs('admin.discounts.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.548 16.596l.344-.343m-2.252-2.252l2.252 2.252m-2.594-5.835l2.594 2.594m0 0l2.252-2.252m-2.252 2.252l-2.252-2.252m7.642 2.252l2.252-2.252m-2.252 2.252l-2.252-2.252m2.252 2.252l-.344.343m0 0l-2.594-2.594m2.594 2.594l2.594-2.594m-5.188-2.594l-2.594 2.594m2.594-2.594l2.594 2.594m-2.594-2.594l-2.594-2.594m5.188 5.188l-2.594-2.594m2.594 2.594l2.594 2.594m0 0l2.252 2.252m-2.252-2.252l2.252-2.252" />
            </svg>
            <span>Diskon</span>
        </x-nav-link>

        <h3 class="px-3 mt-6 mb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">Manajemen Stok</h3>
        <x-nav-link :href="route('admin.procurements.index')" :active="request()->routeIs('admin.procurements.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>Pengadaan</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.stock-opnames.index')" :active="request()->routeIs('admin.stock-opnames.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
            </svg>
            <span>Stok Opname</span>
        </x-nav-link>

        <h3 class="px-3 mt-6 mb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">Manajemen Umum</h3>
        <x-nav-link :href="route('admin.transactions.index')" :active="request()->routeIs('admin.transactions.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.03 1.125 0 1.131.094 1.976 1.057 1.976 2.192V7.5M8.25 7.5h7.5m-7.5 0-1 9.75L8.25 21h7.5l.982-9.75h-9.75Z" />
            </svg>
            <span>Semua Transaksi</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-2.253-9.527 9.527 0 0 0-4.121-2.253M15 19.128v-3.857M15 19.128c-1.56 0-3.078-.756-4.121-2.253M15 19.128V15M10.5 17.25h.008v.008h-.008v-.008Zm0 0c0 .065.008.128.023.191a4.5 4.5 0 0 1-1.135 2.472M8.25 15a4.5 4.5 0 0 1 4.5-4.5m0 0a4.5 4.5 0 0 1 4.5 4.5M3 15a4.5 4.5 0 0 1 4.5-4.5m0 0a4.5 4.5 0 0 1 4.5 4.5" />
            </svg>
            <span>Pengguna</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.members.index')" :active="request()->routeIs('admin.members.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m-7.512 2.72a3 3 0 0 1-4.682-2.72 9.094 9.094 0 0 1 3.741-.479m7.512 2.72a8.97 8.97 0 0 1-7.512 0m7.512 0a3 3 0 0 0-3.741-3.24M3 18.72a3 3 0 0 1-2.24-1.24a3 3 0 0 1-1.5-3.24m15.356 6.48a3 3 0 0 1-4.682-2.72 3 3 0 0 1 1.5-3.24m-4.682 5.96a9.094 9.094 0 0 0 3.741-.479m-3.741.479a3 3 0 0 1-4.682-2.72m-4.682 5.96a3 3 0 0 0-1.5-3.24m0 0a3 3 0 0 1 1.5-3.24m0 0a3 3 0 0 1 4.682 2.72m0 0a3 3 0 0 1-4.682 2.72" />
            </svg>
            <span>Member</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.suppliers.index')" :active="request()->routeIs('admin.suppliers.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6.375a.75.75 0 0 1 .75.75v3.375a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75V7.5a.75.75 0 0 1 .75-.75Zm0 9.375h6.375a.75.75 0 0 1 .75.75v3.375a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75V16.5a.75.75 0 0 1 .75-.75Z" />
            </svg>
            <span>Supplier</span>
        </x-nav-link>

        <h3 class="px-3 mt-6 mb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">Sistem</h3>
        <x-nav-link :href="route('admin.payment-methods.index')"
            :active="request()->routeIs('admin.payment-methods.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 21Z" />
            </svg>
            <span>Metode Pembayaran</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.activity-logs.index')" :active="request()->routeIs('activity_logs.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <span>Log Aktivitas</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.438.995s.145.755.438.995l1.003.827c.446.368.527 1.01.26 1.431l-1.296 2.247a1.125 1.125 0 0 1-1.37.49l-1.217-.456c-.355-.133-.75-.072-1.075.124a6.57 6.57 0 0 1-.22.127c-.331.183-.581.495-.645.87l-.213 1.281c-.09.543-.56.94-1.11.94h-2.593c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.063-.374-.313-.686-.645-.87a6.52 6.52 0 0 1-.22-.127c-.324-.196-.72-.257-1.075-.124l-1.217.456a1.125 1.125 0 0 1-1.37-.49l-1.296-2.247a1.125 1.125 0 0 1 .26-1.431l1.003-.827c.293-.24.438-.613.438.995s-.145-.755-.438-.995l-1.003-.827a1.125 1.125 0 0 1-.26-1.431l1.296-2.247a1.125 1.125 0 0 1 1.37-.49l1.217.456c.355.133.75.072 1.075.124.073.044.146.087.22.127.332.183.581.495.645.87l.213 1.281Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <span>Pengaturan</span>
        </x-nav-link>
        @endif

        {{-- =============================================== --}}
        {{-- MENU KHUSUS KASIR --}}
        {{-- =============================================== --}}
        @if (Auth::user()->isKasir())
        <h3 class="px-3 mt-6 mb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">Menu Kasir</h3>
        <x-nav-link :href="route('kasir.transactions.history')"
            :active="request()->routeIs('kasir.transactions.history')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
            <span>Riwayat Transaksi</span>
        </x-nav-link>
        <x-nav-link :href="route('kasir.members.index')" :active="request()->routeIs('kasir.members.*')">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m-7.512 2.72a3 3 0 0 1-4.682-2.72 9.094 9.094 0 0 1 3.741-.479m7.512 2.72a8.97 8.97 0 0 1-7.512 0m7.512 0a3 3 0 0 0-3.741-3.24M3 18.72a3 3 0 0 1-2.24-1.24a3 3 0 0 1-1.5-3.24m15.356 6.48a3 3 0 0 1-4.682-2.72 3 3 0 0 1 1.5-3.24m-4.682 5.96a9.094 9.094 0 0 0 3.741-.479m-3.741.479a3 3 0 0 1-4.682-2.72m-4.682 5.96a3 3 0 0 0-1.5-3.24m0 0a3 3 0 0 1 1.5-3.24m0 0a3 3 0 0 1 4.682 2.72m0 0a3 3 0 0 1-4.682 2.72" />
            </svg>
            <span>Manajemen Member</span>
        </x-nav-link>
        @endif
    </nav>
</aside>