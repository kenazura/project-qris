<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MembershipController extends Controller
{
    /**
     * Menyimpan data pendaftaran member baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi input: Pastikan payment_method juga divalidasi
        $request->validate([
            'user_telegram'  => 'required|string|max:255',
            'package_name'   => 'required|string',
            'payment_method' => 'required|string', // Menangkap DANA/GOPAY
        ]);

        try {
            // 2. Simpan data ke database
            // PASTIKAN: Anda sudah menambah kolom 'payment_method' di tabel memberships via migration
            $membership = Membership::create([
                'user_telegram'  => $request->user_telegram,
                'package_name'   => $request->package_name,
                'payment_method' => strtoupper($request->payment_method), // Disimpan sebagai DANA atau GOPAY
                'status'         => 'pending', 
                'expired_date'   => null,      
            ]);

            // 3. Kirim notifikasi Telegram
            $this->sendTelegramNotification($membership);

            // 4. Arahkan ke halaman invoice
            return redirect('/invoice/' . $membership->id)
                   ->with('success', 'Pendaftaran berhasil! Silakan selesaikan pembayaran.');

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan membership: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem.']);
        }
    }

    /**
     * Fungsi kirim notifikasi ke Telegram
     */
    private function sendTelegramNotification($membership)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');
        
        if (!$token || !$chatId) {
            Log::warning('Telegram bot config tidak ditemukan');
            return;
        }

        // Tentukan nomor tujuan berdasarkan metode pembayaran
        $nomor = ($membership->payment_method == 'DANA') ? '08123456789' : '08987654321';
        
        $msg = "🔔 *Member Baru Terdeteksi!*\n\n" .
               "🆔 Order ID: #INV-{$membership->id}\n" .
               "👤 Username: @{$membership->user_telegram}\n" .
               "📦 Paket: {$membership->package_name}\n" .
               "💸 Metode: {$membership->payment_method}\n" .
               "💳 Status: Pending\n\n" .
               "Segera buka dashboard admin untuk memproses.";

        try {
            Http::timeout(5)->get("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id'    => $chatId,
                'text'       => $msg,
                'parse_mode' => 'Markdown'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal kirim notifikasi Telegram: ' . $e->getMessage());
        }
    }
}