<?php

// Catch everything and display it
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "<pre>ERROR [$errno]: $errstr\nFile: $errfile\nLine: $errline</pre>";
    exit(1);
});

set_exception_handler(function($e) {
    echo "<pre>EXCEPTION: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "</pre>";
    exit(1);
});

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo "<pre>FATAL: " . $error['message'] . "\nFile: " . $error['file'] . "\nLine: " . $error['line'] . "</pre>";
    }
});

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Setup /tmp directories
$tmp = '/tmp';
foreach ([
    "$tmp/storage/framework/views",
    "$tmp/storage/framework/cache/data",
    "$tmp/storage/framework/sessions",
    "$tmp/storage/framework/testing",
    "$tmp/storage/logs",
    "$tmp/storage/app/public",
    "$tmp/database",
] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0775, true);
}

// SQLite
$db = "$tmp/database/database.sqlite";
if (!file_exists($db)) touch($db);

// Env overrides
foreach ([
    'APP_ENV'              => 'production',
    'APP_DEBUG'            => 'true',
    'DB_CONNECTION'        => 'sqlite',
    'DB_DATABASE'          => $db,
    'SESSION_DRIVER'       => 'cookie',
    'CACHE_STORE'          => 'array',
    'QUEUE_CONNECTION'     => 'sync',
    'LOG_CHANNEL'          => 'stderr',
    'STORAGE_PATH'         => "$tmp/storage",
    'BROADCAST_CONNECTION' => 'log',
] as $k => $v) {
    putenv("$k=$v");
    $_ENV[$k] = $_SERVER[$k] = $v;
}

require __DIR__ . '/../public/index.php';
