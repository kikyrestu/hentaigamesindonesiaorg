<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel...
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Vercel Read-Only Filesystem Fix
// Redirect all storage operations to /tmp (which is writable in Lambda)
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0777, true);
    mkdir($storagePath . '/framework/cache', 0777, true);
    mkdir($storagePath . '/framework/views', 0777, true);
    mkdir($storagePath . '/framework/sessions', 0777, true);
    mkdir($storagePath . '/logs', 0777, true);
}
$app->useStoragePath($storagePath);

// Handle the request
$app->handleRequest(Request::capture());
