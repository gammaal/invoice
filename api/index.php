<?php

// Show errors for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Vercel's only writable directory is /tmp
$tmpDir = '/tmp';

// Create all required Laravel directories in /tmp
$dirs = [
    $tmpDir . '/storage/framework/views',
    $tmpDir . '/storage/framework/cache/data',
    $tmpDir . '/storage/framework/sessions',
    $tmpDir . '/storage/framework/testing',
    $tmpDir . '/storage/logs',
    $tmpDir . '/storage/app/public',
    $tmpDir . '/database',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

// SQLite database in /tmp
$dbPath = $tmpDir . '/database/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
}

// Override environment variables for Vercel serverless
$env = [
    'APP_ENV'             => 'production',
    'APP_DEBUG'           => 'false',
    'DB_CONNECTION'       => 'sqlite',
    'DB_DATABASE'         => $dbPath,
    'SESSION_DRIVER'      => 'cookie',
    'CACHE_STORE'         => 'array',
    'CACHE_DRIVER'        => 'array',
    'QUEUE_CONNECTION'    => 'sync',
    'LOG_CHANNEL'         => 'stderr',
    'FILESYSTEM_DISK'     => 'local',
    'BROADCAST_CONNECTION'=> 'log',
    'VIEW_COMPILED_PATH'  => $tmpDir . '/storage/framework/views',
];

foreach ($env as $key => $value) {
    putenv("{$key}={$value}");
    $_ENV[$key]    = $value;
    $_SERVER[$key] = $value;
}

// Symlink storage directories so Laravel can write to /tmp
$laravelRoot = dirname(__DIR__);
$storageLink = $laravelRoot . '/storage/framework';

// If storage/framework is not writable, override the paths Laravel uses
if (!is_writable($storageLink)) {
    // Override storage path via environment so AppServiceProvider can use it
    putenv('STORAGE_PATH=' . $tmpDir . '/storage');
    $_ENV['STORAGE_PATH']    = $tmpDir . '/storage';
    $_SERVER['STORAGE_PATH'] = $tmpDir . '/storage';
}

require __DIR__ . '/../public/index.php';
