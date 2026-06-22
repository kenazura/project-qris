<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Membership extends Model
{
    // Mengizinkan mass-assignment untuk kolom-kolom yang diperlukan
    protected $fillable = [
        'user_telegram', 
        'package_name', 
        'payment_method', // DITAMBAHKAN untuk sinkronisasi metode bayar
        'expired_date', 
        'status',
        'price' 
    ];

    // Casts: Mengubah format kolom secara otomatis saat diakses
    protected $casts = [
        'expired_date' => 'datetime',
    ];

    /**
     * Relasi ke transaksi (Pastikan model Transaction sudah ada)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * ACCESSOR: Hitung sisa hari secara otomatis
     * Contoh panggil di blade: $membership->sisa_hari
     */
    public function getSisaHariAttribute()
    {
        if (!$this->expired_date || $this->status !== 'active') {
            return 'N/A';
        }

        $now = Carbon::now();
        $expired = Carbon::parse($this->expired_date);

        if ($now->greaterThan($expired)) {
            return 'Expired';
        }

        return $now->diffInDays($expired) . ' hari lagi';
    }

    /**
     * ACCESSOR: Cek apakah paket masih aktif
     * Contoh panggil di blade: if($membership->is_active) { ... }
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               ($this->expired_date === null || Carbon::now()->lessThan($this->expired_date));
    }

    /**
     * SCOPE: Memudahkan filter status di controller/admin dashboard
     * Contoh: Membership::pending()->get();
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}