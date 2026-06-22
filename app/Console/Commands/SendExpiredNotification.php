<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendExpiredNotification extends Command
{
    protected $signature = 'notify:expired';
    protected $description = 'Kirim notifikasi Telegram dan update status member yang expired';

    public function handle()
    {
        $now = Carbon::now();
        $this->info("Waktu server saat ini: " . $now);

        // --- DEBUG & PROSES EXPIRED ---
        $allActive = Membership::where('status', 'active')->get();
        
        foreach ($allActive as $member) {
            $expiredDate = Carbon::parse($member->expired_date);
            
            // Logika Deteksi: Jika expired_date lebih kecil atau sama dengan sekarang
            if ($expiredDate->lte($now)) {
                $this->info("Member {$member->user_telegram} terdeteksi EXPIRED (Date: {$member->expired_date})");
                
                $msg = "⚠️ *Masa Aktif Habis*\n\nUsername: {$member->user_telegram}\nPaket: {$member->package_name}\nStatus: Telah Expired.";
                
                if ($this->sendTelegram($msg)) {
                    $member->update(['status' => 'expired']);
                    $this->info("Berhasil update status member {$member->user_telegram}");
                }
            } else {
                $this->info("Member {$member->user_telegram} masih AKTIF (Expired: {$member->expired_date})");
            }
        }

        // --- PROSES REMINDER H-3 ---
        $reminderDate = Carbon::today()->addDays(3);
        $reminders = Membership::where('status', 'active')
            ->whereNotNull('expired_date')
            ->whereDate('expired_date', $reminderDate)
            ->get();

        foreach ($reminders as $member) {
            $msg = "⏳ *Reminder Masa Aktif*\n\nUsername: {$member->user_telegram}\nPaket: {$member->package_name}\nAkan habis dalam 3 hari.";
            $this->sendTelegram($msg);
            $this->info("Reminder dikirim: {$member->user_telegram}");
        }
    }

    private function sendTelegram($message)
    {
        try {
            $token = "8468190255:AAGCMW9AiMddqNN3PelVfzyiQMvCX3BG7XE";
            $chatId = "8503613163";
            
            $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown'
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Error Telegram: " . $e->getMessage());
            return false;
        }
    }
}