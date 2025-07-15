<!-- Header -->
<header class="flex items-center justify-between h-20 px-6 py-4 bg-white dark:bg-gray-900 border-b dark:border-gray-700">
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Button -->
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 dark:text-gray-300 focus:outline-none lg:hidden">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>

        {{-- Menampilkan waktu server untuk debugging --}}
        <div 
            x-data="{ currentTime: new Date() }" 
            x-init="setInterval(() => { currentTime = new Date() }, 1000)"
            class="hidden md:block text-xs text-gray-500 dark:text-gray-400"
        >
            <div x-text="currentTime.toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'medium' })"></div>
            <div>Timezone Aplikasi: {{ config('app.timezone') }}</div>
        </div>
    </div>

    <div class="flex items-center space-x-4">
        <!-- Dark Mode Toggle -->
        <button @click="darkMode = !darkMode" class="text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 transition-colors duration-200">
            <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
            <svg x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 5.05A1 1 0 003.636 6.464l.707.707a1 1 0 001.414-1.414l-.707-.707zM3 11a1 1 0 100-2H2a1 1 0 100 2h1zM6.464 16.364a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707z"></path></svg>
        </button>

        <!-- User Dropdown -->
        <div class="relative" x-data="{ dropdownOpen: false }">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 relative focus:outline-none">
                <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</h2>
                <img class="h-9 w-9 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="Avatar Pengguna">
            </button>

            <div 
                x-show="dropdownOpen" 
                @click.away="dropdownOpen = false" 
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 w-48 mt-2 origin-top-right bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            >
                <div class="py-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Profil</a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a 
                            href="{{ route('logout') }}" 
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            Keluar
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
