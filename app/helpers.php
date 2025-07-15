<?php

if (! function_exists('format_rupiah')) {
    /**
     * Mengubah angka menjadi format mata uang Rupiah.
     *
     * @param int|float $number Angka yang akan diformat.
     * @return string Angka dalam format Rupiah (contoh: Rp 100.000).
     */
    function format_rupiah($number)
    {
        return 'Rp' . number_format($number, 0, ',', '.');
    }
}

if (! function_exists('setting')) {
    /**
     * Mengambil nilai dari tabel settings dengan caching.
     *
     * @param string $key Kunci dari setting yang ingin diambil.
     * @param mixed $default Nilai default jika setting tidak ditemukan.
     * @return mixed
     */
    function setting($key, $default = null)
    {
        // Ambil dari cache jika ada, jika tidak, ambil dari DB dan simpan ke cache selamanya
        $settings = \Illuminate\Support\Facades\Cache::rememberForever('app_settings', function () {
            return \App\Models\Setting::all()->keyBy('key');
        });

        // Kembalikan nilai dari setting, atau nilai default jika tidak ada
        return $settings->get($key)->value ?? $default;
    }
}



