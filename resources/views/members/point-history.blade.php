<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Poin untuk ') }} <span class="text-indigo-600 dark:text-indigo-400">{{ $member->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-center">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Poin Saat Ini</p>
                    <p class="mt-1 text-4xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $member->points }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Detail Riwayat</h3>
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @forelse ($histories as $history)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                @if($history->type === 'earn')
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                                    </span>
                                                @else
                                                    <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" /></svg>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $history->description }}
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap">
                                                    <span class="font-bold {{ $history->type === 'earn' ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ $history->points > 0 ? '+' : '' }}{{ $history->points }} Poin
                                                    </span>
                                                    <p class="text-gray-500 dark:text-gray-400">{{ $history->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-500">Belum ada riwayat poin untuk member ini.</p>
                                </div>
                            @endforelse
                        </ul>
                    </div>
                    <div class="mt-6">
                        {{ $histories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
