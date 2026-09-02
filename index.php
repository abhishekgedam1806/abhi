<?php

/**
 * Laravel Root Proxy for Shared Hosting (Hostinger / cPanel)
 */

define('LARAVEL_START', microtime(true));

// Check if running directly from public
if (file_exists(__DIR__ . '/public/index.php')) {
    require __DIR__ . '/public/index.php';
} else {
    echo "Laravel public folder not found.";
}
