<?php

// Self-deleting route cache: deletes itself and config cache, then refreshes the page
@unlink(__FILE__);
@unlink(__DIR__ . '/config.php');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

header("Location: " . $url);
exit();
