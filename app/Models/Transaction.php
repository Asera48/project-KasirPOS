<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'member_id',
        'transaction_date',
        'total',
        'paid',
        'change',
        'invoice_number',
        'payment_method',
        'status',
        'subtotal',
        'tax_amount',
        'rounding_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'transaction_date' => 'datetime',
        ];
    }

    /**
     * Mendapatkan pengguna (kasir) yang melakukan transaksi.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan member yang terkait dengan transaksi (jika ada).
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Mendapatkan semua item dalam transaksi ini.
     */
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
