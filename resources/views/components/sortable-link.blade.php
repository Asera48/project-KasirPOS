@props(['column', 'label'])

@php
    // Menentukan arah pengurutan saat ini dan yang baru
    $direction = request('direction', 'asc');
    $sortColumn = request('sort', 'created_at'); 
    $arrow = '';
    $newDirection = 'asc';

    if ($sortColumn == $column) {
        $arrow = $direction == 'asc' ? '&uarr;' : '&darr;';
        $newDirection = $direction == 'asc' ? 'desc' : 'asc';
    }

    // Mempertahankan parameter query yang sudah ada (seperti pencarian)
    $queryParams = array_merge(request()->query(), [
        'sort' => $column,
        'direction' => $newDirection,
    ]);
@endphp

<a href="{{ route(request()->route()->getName(), $queryParams) }}" class="inline-flex items-center">
    <span>{{ $label }}</span>
    {{-- Tampilkan panah jika kolom ini sedang aktif --}}
    <span class="ml-1 text-gray-400">{!! $arrow !!}</span>
</a>
