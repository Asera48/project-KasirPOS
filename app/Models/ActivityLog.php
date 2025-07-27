<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
    ];

    /**
     * Mendapatkan pengguna yang melakukan aktivitas.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function addLog(string $action, string $description): void
    {
        self::create([
            'user_id' => Auth::id(), 
            'action' => $action,
            'description' => $description,
        ]);
    }
}
