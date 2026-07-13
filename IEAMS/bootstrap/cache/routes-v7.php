<?php

// Check if running in CLI (composer/artisan) to avoid crashing runner
if (php_sapi_name() === 'cli') {
    return [];
}

// Self-deleting route cache: deletes itself and config cache, then refreshes the page
@unlink(__FILE__);
@unlink(__DIR__ . '/config.php');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

header("Location: " . $protocol . $host . $uri);
exit();
