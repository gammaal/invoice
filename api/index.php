<?php

// Buat SQLite database di /tmp (writable di Vercel)
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
}

// Set environment variable agar Laravel pakai /tmp/database.sqlite
$_ENV['DB_DATABASE'] = $dbPath;
putenv('DB_DATABASE=' . $dbPath);

require __DIR__ . '/../public/index.php';
