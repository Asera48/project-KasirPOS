<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PointHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'transaction_id',
        'type',
        'points',
        'description',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
