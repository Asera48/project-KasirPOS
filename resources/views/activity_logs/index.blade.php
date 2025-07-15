<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Log Aktivitas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- PERUBAHAN: Form Filter yang Disederhanakan -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Filter Log</h3>
                    <form action="{{ route('admin.activity-logs.index') }}" method="GET">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 items-end">
                            <div>
                                <x-input-label for="filter_date" :value="__('Tampilkan Log untuk Tanggal')" />
                                <x-text-input id="filter_date" type="date" name="filter_date" class="block mt-1 w-full" :value="$filterDate" />
                            </div>
                            <div class="sm:col-span-2">
                                <x-input-label for="search" :value="__('Pencarian Teks (Opsional)')" />
                                <div class="flex">
                                    <input type="text" name="search" id="search" placeholder="Cari aksi, deskripsi, atau nama pengguna..." class="w-full px-4 py-2 border rounded-l-md dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="{{ $search ?? '' }}">
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700">Tampilkan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="space-y-8">
                        @php $currentDate = null; @endphp

                        @forelse ($activityLogs as $log)
                            @if ($currentDate !== $log->created_at->format('Y-m-d'))
                                @php $currentDate = $log->created_at->format('Y-m-d'); @endphp
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700 pb-2 @if(!$loop->first) pt-6 @endif">
                                    @php
                                        $carbonDate = \Carbon\Carbon::parse($currentDate);
                                        if ($carbonDate->isToday()) echo 'Hari Ini';
                                        elseif ($carbonDate->isYesterday()) echo 'Kemarin';
                                        else echo $carbonDate->translatedFormat('l, d F Y');
                                    @endphp
                                </h3>
                            @endif

                            <div class="flow-root">
                                <ul role="list" class="-mb-8">
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last && $activityLogs[$loop->index + 1]->created_at->format('Y-m-d') === $currentDate)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    @php
                                                        $actionType = Str::before($log->action, '_');
                                                        $colorClasses = [
                                                            'CREATE' => 'bg-green-500', 'UPDATE' => 'bg-blue-500',
                                                            'DELETE' => 'bg-red-500', 'LOGIN' => 'bg-yellow-500',
                                                            'STOCK' => 'bg-purple-500',
                                                        ];
                                                        $bgColor = $colorClasses[$actionType] ?? 'bg-gray-500';
                                                    @endphp
                                                    <span class="h-8 w-8 rounded-full {{ $bgColor }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        {{-- SVG Icons --}}
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                                            <span class="font-medium text-gray-900 dark:text-white">{{ $log->user->name ?? 'Sistem' }}</span>
                                                            - <span class="font-medium">{{ $log->description }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                        <time datetime="{{ $log->created_at->toIso8601String() }}">{{ $log->created_at->format('H:i') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-gray-500">Tidak ada log aktivitas yang ditemukan untuk filter ini.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $activityLogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
