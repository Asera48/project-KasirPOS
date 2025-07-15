<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcurementItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'procurement_id',
        'product_id',
        'quantity',
        'cost_price',
    ];

    /**
     * Mendapatkan pengadaan induk dari item ini.
     */
    public function procurement()
    {
        return $this->belongsTo(Procurement::class);
    }

    /**
     * Mendapatkan produk yang terkait dengan item ini.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
