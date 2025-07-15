<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'points',
    ];

    /**
     * Mendapatkan semua transaksi yang dilakukan oleh member ini.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Mendapatkan semua riwayat poin untuk member ini.
     */
    public function pointHistories()
    {
        return $this->hasMany(PointHistory::class);
    }
}
