<?php
// api/index.php

// Pastikan library vendor terload
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Jalankan Kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// KIRIM RESPON SEBAGAI HTML
header('Content-Type: text/html');
$response->send();

$kernel->terminate($request, $response);