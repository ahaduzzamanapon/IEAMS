<?php

// Auto-run pending migrations and clear cache after FTP deployment
if (file_exists(__DIR__ . '/../bootstrap/cache/needs_migrate.php')) {
    try {
        require __DIR__ . '/../vendor/autoload.php';
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        
        // Execute migrations
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        
        // Clear caches
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        
        // Delete all caches and trigger file
        @unlink(__DIR__ . '/../bootstrap/cache/needs_migrate.php');
        @unlink(__DIR__ . '/../bootstrap/cache/config.php');
        @unlink(__DIR__ . '/../bootstrap/cache/routes-v7.php');
        
        // Refresh page to load dynamically
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        header("Location: " . $protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/'));
        exit();
    } catch (\Exception $e) {
        @unlink(__DIR__ . '/../bootstrap/cache/needs_migrate.php');
        die("Post-deployment setup failed: " . $e->getMessage());
    }
}

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
