<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'system_stock',
        'physical_stock',
        'note',
    ];

    /**
     * Mendapatkan produk yang di-opname.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mendapatkan pengguna yang melakukan opname.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
