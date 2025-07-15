<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProcurementItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProcurementImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $procurement;

    public function __construct($procurement)
    {
        $this->procurement = $procurement;
    }

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            // PERBAIKAN: Lewati baris jika kolom 'jumlah' kosong atau 0
            if (empty($row['jumlah'])) {
                continue;
            }

            // Cari produk berdasarkan barcode/SKU
            $product = Product::where('barcode', $row['barcode_sku'])->first();

            if ($product) {
                $quantity = $row['jumlah'];
                $costPrice = $row['harga_pokok_satuan'];

                // Buat item pengadaan
                ProcurementItem::create([
                    'procurement_id' => $this->procurement->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'cost_price' => $costPrice,
                ]);

                // Hitung harga pokok rata-rata baru
                $oldStock = $product->stock;
                $oldCostPrice = $product->cost_price;
                
                $totalOldValue = $oldStock * $oldCostPrice;
                $totalNewValue = $quantity * $costPrice;
                $totalStock = $oldStock + $quantity;

                $newAverageCost = ($totalStock > 0) ? (($totalOldValue + $totalNewValue) / $totalStock) : $costPrice;

                // Update harga pokok dan stok produk
                $product->update([
                    'cost_price' => round($newAverageCost),
                    'stock' => $totalStock
                ]);
            }
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        // PERBAIKAN: Membuat validasi lebih fleksibel
        return [
            'barcode_sku' => 'required|exists:products,barcode',
            'jumlah' => 'nullable|integer|min:1',
            'harga_pokok_satuan' => 'required_with:jumlah|nullable|integer|min:0',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'barcode_sku.required' => 'Kolom barcode_sku tidak boleh kosong.',
            'barcode_sku.exists' => 'Barcode/SKU :input tidak ditemukan di database produk.',
            'harga_pokok_satuan.required_with' => 'Kolom harga_pokok_satuan wajib diisi jika kolom jumlah diisi.',
        ];
    }
}
