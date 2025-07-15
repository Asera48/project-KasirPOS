    @props(['active'])

    @php
    $classes = ($active ?? false)
    ? 'flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md transition-colors
    duration-150'
    : 'flex items-center px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900
    dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md transition-colors duration-150';
    @endphp

    <a {{ $attributes->merge(['class' => $classes . ' space-x-3']) }}>
        {{ $slot }}
    </a>