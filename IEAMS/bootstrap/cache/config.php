<?php

// Self-deleting config cache: deletes itself and route cache, then refreshes the page
@unlink(__FILE__);
@unlink(__DIR__ . '/routes-v7.php');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

header("Location: " . $url);
exit();
