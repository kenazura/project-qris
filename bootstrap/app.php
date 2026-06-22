<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Mendaftarkan middleware untuk proteksi halaman admin
        $middleware->alias([
            'admin.protect' => \App\Http\Middleware\AdminProtect::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Konfigurasi agar error di API muncul dalam format JSON
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })
    ->withSchedule(function ($schedule) {
        // Mendaftarkan Scheduler secara eksplisit agar Command notify:expired berjalan otomatis
        $schedule->command('notify:expired')->dailyAt('07:00');
    })
    ->create();