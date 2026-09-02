<?php
//echo '<pre>';
//print_r(get_loaded_extensions());
//exit;
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// If file exists in public directory, serve it permanently
$publicFile = __DIR__ . '/public' . $uri;

if ($uri !== '/' && file_exists($publicFile) && !is_dir($publicFile)) {
    $extension = strtolower(pathinfo($publicFile, PATHINFO_EXTENSION));
    $mimes = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'application/javascript; charset=UTF-8',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
        'otf' => 'font/otf',
        'map' => 'application/json',
        'webp' => 'image/webp',
        'mp4' => 'video/mp4',
        'pdf' => 'application/pdf',
    ];
    if (isset($mimes[$extension])) {
        header('Content-Type: ' . $mimes[$extension]);
    }
    header('Access-Control-Allow-Origin: *');
    header('Cache-Control: public, max-age=86400');
    readfile($publicFile);
    exit;
}

require_once __DIR__.'/public/index.php';
