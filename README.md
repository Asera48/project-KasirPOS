# Aplikasi Kasir (POS) Berbasis Laravel

![Tampilan Dasbor Kasir](https://i.imgur.com/lFe8fRE.png) 
Aplikasi ini adalah sistem Point of Sale (POS) berbasis web, dibangun menggunakan Laravel 12. Dirancang untuk mengelola operasional toko ritel skala kecil hingga menengah, aplikasi ini mencakup fungsionalitas manajemen transaksi, inventaris, pelanggan, dan laporan dalam satu platform yang mudah digunakan. 
Galeri screenshot [here](https://drive.google.com/drive/folders/1uqrMMeLXBy0qQEEtIlUaGfoILd37Nw2d?usp=sharing).
---

## Fitur Utama

Aplikasi ini dilengkapi dengan berbagai fitur yang terbagi menjadi dua peran utama: **Admin** dan **Kasir**.

### Fitur Kasir
- **Dasbor Point of Sale (POS):** Antarmuka utama yang cepat dan responsif untuk melakukan transaksi.
- **Pencarian Produk Real-time:** Cari produk secara instan berdasarkan nama atau barcode/SKU tanpa me-refresh halaman.
- **Manajemen Keranjang:** Tambah, ubah kuantitas, dan hapus item dari keranjang dengan mudah.
- **Penerapan Diskon Otomatis:** Sistem secara otomatis menghitung diskon produk (persentase atau tetap) yang sedang aktif.
- **Sistem Poin Member:**
    - Pilih pelanggan terdaftar untuk setiap transaksi.
    - Tukarkan (redeem) poin member sebagai potongan harga.
    - Poin baru akan otomatis ditambahkan setelah transaksi selesai.
- **Perhitungan Otomatis:** Sistem secara otomatis menghitung subtotal, pajak (dengan tarif dinamis), pembulatan, dan uang kembalian.
- **Riwayat Transaksi:** Kasir dapat melihat riwayat transaksi yang telah mereka lakukan sendiri, lengkap dengan filter tanggal.
- **Cetak Struk:** Cetak struk transaksi yang rapi dan informatif.

### Fitur Admin
- **Dasbor Analitik:** Menampilkan ringkasan statistik bisnis, termasuk total produk, transaksi, pengguna, serta grafik pendapatan 7 hari terakhir.
- **Panel Informasi Cepat:** Menampilkan daftar "Produk Terlaris" dan "Stok Akan Habis" secara dinamis.
- **Manajemen Produk (CRUD):** Pengelolaan penuh data produk, termasuk nama, kategori, harga jual, harga pokok, stok, barcode, dan upload gambar.
- **Manajemen Inventaris:**
    - **Pengadaan Barang:** Catat barang masuk dari supplier secara manual atau melalui **impor dari file Excel**.
    - **Stok Opname:** Lakukan penyesuaian stok fisik dengan stok di sistem.
- **Manajemen Penjualan:**
    - **Diskon:** Kelola diskon berbasis periode untuk produk tertentu.
    - **Member:** Pengelolaan penuh data pelanggan (member) dan riwayat perolehan/penukaran poin mereka.
- **Manajemen Umum (CRUD):**
    - **Supplier:** Kelola data pemasok barang.
    - **Pengguna:** Kelola akun untuk admin dan kasir.
- **Laporan:**
    - **Laporan Penjualan:** Lihat laporan penjualan dengan filter rentang tanggal, lengkap dengan ringkasan pendapatan dan laba. Fitur **Cetak** dan **Ekspor ke Excel**.
    - **Laporan Stok:** Lihat laporan stok produk dan filter produk yang stoknya menipis.
- **Pengaturan Sistem:**
    - **Pengaturan Aplikasi:** Ubah nama aplikasi, logo, alamat, dan nomor telepon toko secara dinamis.
    - **Metode Pembayaran:** Tambah, edit, atau nonaktifkan metode pembayaran 
    - **Log Aktivitas:** Pantau semua aktivitas penting yang terjadi di dalam sistem.

---

## Teknologi yang Digunakan

- **Backend:** Laravel 12 (PHP 8.3)
- **Frontend:** Tailwind CSS 3, Alpine.js
- **Database:** MySQL
- **Server Lokal:** Laragon
- **Paket Utama:**
    - `laravel/breeze`: Untuk sistem otentikasi.
    - `laravel/sanctum`: Untuk otentikasi API.
    - `maatwebsite/excel`: Untuk fitur impor dan ekspor data Excel.
