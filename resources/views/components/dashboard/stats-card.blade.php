@props(['title', 'value', 'color' => 'gray'])

@php
    // Logika untuk menentukan kelas warna berdasarkan properti 'color'
    $colorClasses = [
        'indigo' => 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-500/20',
        'green' => 'text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-500/20',
        'purple' => 'text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-500/20',
        'gray' => 'text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700',
    ];
    
    $valueColorClass = [
        'indigo' => 'text-indigo-600 dark:text-indigo-400',
        'green' => 'text-green-600 dark:text-green-400',
        'purple' => 'text-purple-600 dark:text-purple-400',
        'gray' => 'text-gray-600 dark:text-gray-400',
    ];

    $iconContainerClass = $colorClasses[$color] ?? $colorClasses['gray'];
    $valueClass = $valueColorClass[$color] ?? $valueColorClass['gray'];
@endphp

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg flex items-center justify-between">
    <div>
        <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300">{{ $title }}</h3>
        <p class="text-3xl font-bold mt-2 {{ $valueClass }}">{{ $value }}</p>
    </div>
    @if (isset($icon))
    <div class="{{ $iconContainerClass }} p-3 rounded-full">
        {{-- Slot 'icon' akan diisi dengan SVG dari file utama --}}
        <span class="{{ $valueClass }}">
            {{ $icon }}
        </span>
    </div>
    @endif
</div>
