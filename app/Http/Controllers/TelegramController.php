<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Membership; 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    /**
     * 1. Webhook dari Telegram
     * Menerima pesan masuk dari user
     */
    public function handle(Request $request)
    {
        $update = $request->all();
        
        // Log untuk debugging jika ada error di Vercel
        Log::info('Telegram Webhook:', $update);
        
        if (isset($update['message']['text'])) {
            $text = $update['message']['text'];
            $chatId = $update['message']['chat']['id'];

            if (str_contains(strtoupper($text), '#KONFIRMASI')) {
                $orderId = trim(str_replace('#KONFIRMASI', '', strtoupper($text)));
                $transaction = Transaction::where('order_id', $orderId)->first();

                if ($transaction) {
                    $transaction->update(['status' => 'success']);
                    $this->sendMessage($chatId, "Berhasil! Pembayaran untuk $orderId telah dikonfirmasi.");
                } else {
                    $this->sendMessage($chatId, "Maaf, Order ID $orderId tidak ditemukan.");
                }
            }
        }
        return response()->json(['status' => 'ok']);
    }

    /**
     * 2. Logika untuk Cron Job (Cek Expired)
     * Dipanggil oleh cron-job.org
     */
    public function checkExpired(Request $request)
    {
        // Proteksi keamanan agar tidak bisa diakses sembarang orang
        if ($request->query('key') !== env('CRON_SECRET_KEY')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Cari transaksi yang statusnya masih 'pending' dan sudah melewati tanggal expired
        $expiredTransactions = Transaction::where('status', 'pending')
            ->where('expired_at', '<', now())
            ->get();

        foreach ($expiredTransactions as $transaction) {
            // Update status ke expired
            $transaction->update(['status' => 'expired']);

            // Kirim notifikasi ke admin
            $adminChatId = env('TELEGRAM_ADMIN_CHAT_ID');
            $this->sendMessage($adminChatId, "Order {$transaction->order_id} telah expired.");
        }

        return response()->json([
            'message' => 'Pengecekan selesai', 
            'expired_count' => $expiredTransactions->count()
        ]);
    }

    /**
     * Helper untuk kirim pesan ke Telegram
     */
    private function sendMessage($chatId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        
        // Pastikan token ada sebelum melakukan request
        if (!$token) {
            Log::error('TELEGRAM_BOT_TOKEN tidak ditemukan di environment');
            return;
        }

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text
        ]);
    }
}