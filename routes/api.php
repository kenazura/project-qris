<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;

/**
 * Rute untuk Webhook Telegram
 * Telegram akan mengirim update ke sini menggunakan method POST
 */
Route::post('/telegram-webhook', [TelegramController::class, 'handle']);

/**
 * Rute untuk Cron-job.org
 * Cron-job.org akan memicu fungsi ini secara berkala
 */
Route::get('/cron/notify-expired', [TelegramController::class, 'checkExpired']);