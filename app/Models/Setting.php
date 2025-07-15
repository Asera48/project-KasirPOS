<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    // Memberitahu Laravel bahwa primary key bukan 'id'
    protected $primaryKey = 'key';
    
    // Memberitahu Laravel bahwa primary key bukan auto-incrementing
    public $incrementing = false;

    // Memberitahu Laravel bahwa tipe primary key adalah string
    protected $keyType = 'string';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'key',
        'value',
    ];
}
