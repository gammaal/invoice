<?php

// Tampilkan error untuk debug
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Folder /tmp adalah satu-satunya yang writable di Vercel
$tmpDir = '/tmp';
$dbPath = $tmpDir . '/database.sqlite';

// Buat file SQLite jika belum ada
if (!file_exists($dbPath)) {
    touch($dbPath);
}

// Override env variables untuk Vercel serverless
$envOverrides = [
    'DB_CONNECTION'  => 'sqlite',
    'DB_DATABASE'    => $dbPath,
    'SESSION_DRIVER' => 'cookie',
    'CACHE_STORE'    => 'array',
    'CACHE_DRIVER'   => 'array',
    'QUEUE_CONNECTION' => 'sync',
    'LOG_CHANNEL'    => 'stderr',
    'FILESYSTEM_DISK'=> 'local',
    'APP_ENV'        => 'production',
    'APP_DEBUG'      => 'false',
    'BROADCAST_CONNECTION' => 'log',
];

foreach ($envOverrides as $key => $value) {
    putenv("{$key}={$value}");
    $_ENV[$key]    = $value;
    $_SERVER[$key] = $value;
}

require __DIR__ . '/../public/index.php';
