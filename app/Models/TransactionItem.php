<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price',
        'cost_price',
        'subtotal',
        'discount_type',
        'discount_value',
        'discount_amount',
    ];

    /**
     * Mendapatkan transaksi induk dari item ini.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Mendapatkan produk yang terkait dengan item ini.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
