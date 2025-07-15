<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'category_id', 
        'price', 
        'cost_price',
        'stock', 
        'image',
        'barcode',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function discount()
    {
        return $this->hasOne(Discount::class);
    }
}
