<?php

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo "<pre>ERROR [$errno]: $errstr\nFile: $errfile\nLine: $errline</pre>";
    exit(1);
});
set_exception_handler(function($e) {
    echo "<pre>EXCEPTION: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "</pre>";
    exit(1);
});
register_shutdown_function(function() {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo "<pre>FATAL: " . $e['message'] . "\nFile: " . $e['file'] . "\nLine: " . $e['line'] . "</pre>";
    }
});

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$root = dirname(__DIR__);
$tmp  = '/tmp';

// Create required directories in /tmp
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

// SQLite database
$db = "$tmp/database/database.sqlite";
if (!file_exists($db)) touch($db);

// APP_KEY must be set via Vercel environment variables
// Fallback only for dev - on Vercel set APP_KEY in project settings
$appKey = getenv('APP_KEY') ?: 'base64:NGKWNaB5jJxTtgH74XqyAz9U8MZcLdGPReYTWIgSSt0=';

// Write a .env file to /tmp so phpdotenv can find it
$envContent = <<<ENV
APP_NAME=BengkelApp
APP_ENV=production
APP_KEY={$appKey}
APP_DEBUG=false
APP_URL=https://invoice-iode0mjxu-gamma-alfatahs-projects.vercel.app

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE={$db}

SESSION_DRIVER=cookie
SESSION_LIFETIME=120

CACHE_STORE=array
QUEUE_CONNECTION=sync
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
ENV;

file_put_contents("$tmp/.env", $envContent);

// Also set as actual env vars so they are available immediately
$envVars = [
    'APP_NAME'             => 'BengkelApp',
    'APP_ENV'              => 'production',
    'APP_KEY'              => $appKey,
    'APP_DEBUG'            => 'false',
    'LOG_CHANNEL'          => 'stderr',
    'DB_CONNECTION'        => 'sqlite',
    'DB_DATABASE'          => $db,
    'SESSION_DRIVER'       => 'cookie',
    'CACHE_STORE'          => 'array',
    'QUEUE_CONNECTION'     => 'sync',
    'BROADCAST_CONNECTION' => 'log',
    'FILESYSTEM_DISK'      => 'local',
    'STORAGE_PATH'         => "$tmp/storage",
];

foreach ($envVars as $k => $v) {
    putenv("$k=$v");
    $_ENV[$k] = $_SERVER[$k] = $v;
}

// Override Laravel's base path for .env loading
// Symlink /tmp/.env -> /var/task/user/.env so phpdotenv finds it
$envTarget = "$root/.env";
if (!file_exists($envTarget)) {
    // Can't write to $root on Vercel, so we patch APP_BASE_PATH instead
    // by setting the env vars directly (already done above)
    // phpdotenv will still try to read .env - suppress the warning
    putenv("DOTENV_DISABLE_FILE=true");
}

require __DIR__ . '/../public/index.php';
