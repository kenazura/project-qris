<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Pastikan waktu mulai didefinisikan
define('LARAVEL_START', microtime(true));

// Menggunakan autoloader dari composer
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Menangani request
$request = Request::capture();

// Penting untuk Vercel: Laravel perlu tahu jika berjalan di balik proxy
$app->prepareRequestForFramework($request);

$response = $app->handle($request);

$response->send();

$app->terminate($request, $response);