<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 1. Buat folder sementara (/tmp) yang diizinkan secara hukum oleh Vercel
if (!file_exists('/tmp/storage/logs')) {
    mkdir('/tmp/storage/logs', 0777, true);
}
if (!file_exists('/tmp/bootstrap/cache')) {
    mkdir('/tmp/bootstrap/cache', 0777, true);
}
if (!file_exists('/tmp/storage/framework/views')) {
    mkdir('/tmp/storage/framework/views', 0777, true);
}

// 2. Belokkan paksa Laravel untuk menggunakan folder /tmp tersebut
$app->useStoragePath('/tmp/storage');
$app->useBootstrapPath('/tmp/bootstrap');

// 3. Jalankan aplikasi Laravel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);