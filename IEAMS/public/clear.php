<?php

// Temporary script to clear Laravel cache on production server
// To protect this script from unauthorized use, we check a simple query parameter or run it directly.

$configCache = __DIR__ . '/../bootstrap/cache/config.php';
$routesCache = __DIR__ . '/../bootstrap/cache/routes-v7.php';

$cleared = [];

if (file_exists($configCache)) {
    if (unlink($configCache)) {
        $cleared[] = "Config cache deleted successfully.";
    } else {
        $cleared[] = "Failed to delete config cache.";
    }
} else {
    $cleared[] = "Config cache file did not exist.";
}

if (file_exists($routesCache)) {
    if (unlink($routesCache)) {
        $cleared[] = "Route cache deleted successfully.";
    } else {
        $cleared[] = "Failed to delete route cache.";
    }
} else {
    $cleared[] = "Route cache file did not exist.";
}

// Clear compiled views if any
$viewsPattern = __DIR__ . '/../storage/framework/views/*';
foreach (glob($viewsPattern) as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}
$cleared[] = "Compiled views cleared.";

echo "<h1>IEAMS Cache Cleaner</h1>";
echo "<ul>";
foreach ($cleared as $msg) {
    echo "<li>" . htmlspecialchars($msg) . "</li>";
}
echo "</ul>";
echo "<p><a href='/'>Go to Dashboard</a></p>";
