<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Procurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'procurement_date',
        'total_cost',
        'reference_number',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'procurement_date' => 'datetime',
        ];
    }

    /**
     * Mendapatkan pengguna yang melakukan pengadaan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Mendapatkan supplier yang melakukan pengadaan.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Mendapatkan semua item dalam pengadaan ini.
     */
    public function items()
    {
        return $this->hasMany(ProcurementItem::class);
    }
}
