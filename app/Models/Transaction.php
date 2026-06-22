<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    // Mengizinkan field ini diisi secara massal
    protected $fillable = [
        'order_id',
        'membership_id',
        'amount',
        'status',
    ];

    /**
     * Relasi: Setiap transaksi milik satu member (Membership).
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }
}