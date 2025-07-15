<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProcurementTemplateExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil semua produk beserta relasi kategorinya, diurutkan berdasarkan kategori lalu nama produk
        return Product::with('category')->orderBy('category_id')->orderBy('name')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Ini akan menjadi baris header di file Excel
        return [
            'kategori',
            'nama_produk',
            'barcode_sku',
            'jumlah',
            'harga_pokok_satuan',
        ];
    }

    /**
     * @param mixed $product
     * @return array
     */
    public function map($product): array
    {
        // Ini memetakan setiap data produk ke kolom yang sesuai
        // Kolom 'jumlah' dan 'harga_pokok_satuan' sengaja dikosongkan
        return [
            $product->category->name ?? 'Tanpa Kategori',
            $product->name,
            $product->barcode,
            '', // Kolom jumlah untuk diisi admin
            '', // Kolom harga pokok untuk diisi admin
        ];
    }
}
