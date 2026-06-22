<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

// 1. Definisikan waktu mulai aplikasi
define('LARAVEL_START', microtime(true));

// 2. Pastikan file autoloader tersedia
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    die('Autoloader tidak ditemukan. Pastikan folder vendor sudah ter-upload.');
}

// 3. Bootstrap aplikasi
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Buat kernel HTTP
$kernel = $app->make(Kernel::class);

// 5. Tangkap request
$request = Request::capture();

// 6. Jalankan request melalui kernel
$response = $kernel->handle($request);

// 7. Kirim respons ke browser
$response->send();

// 8. Terminasi proses (penting untuk serverless agar memori dibebaskan)
$kernel->terminate($request, $response);