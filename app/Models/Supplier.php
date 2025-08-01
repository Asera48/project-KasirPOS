<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'address',
    ];
    /**
     * Mendapatkan semua pengadaan dari supplier ini.
     */
    public function procurements()
    {
        return $this->hasMany(Procurement::class);
    }
}
