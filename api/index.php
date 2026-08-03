<?php
// ========================================================
// Vercel Serverless Entrypoint for PHP Applications
// ========================================================

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Normalize URI
if ($uri === '' || $uri === '/') {
    require __DIR__ . '/../index.php';
    exit;
}

// Handle Admin Routes
if ($uri === '/admin' || $uri === '/admin/') {
    require __DIR__ . '/../admin/index.php';
    exit;
}

if (strpos($uri, '/admin/') === 0) {
    $target = __DIR__ . '/..' . $uri;
    if (file_exists($target) && !is_dir($target)) {
        require $target;
    } else {
        require __DIR__ . '/../admin/index.php';
    }
    exit;
}

// Handle API Routes
if (strpos($uri, '/api/') === 0 && $uri !== '/api/index.php') {
    $target = __DIR__ . '/..' . $uri;
    if (file_exists($target) && !is_dir($target)) {
        require $target;
    } else {
        require __DIR__ . '/../api/get_photos.php';
    }
    exit;
}

// Handle Direct Root PHP Files (e.g., /test_db.php)
$root_target = __DIR__ . '/..' . $uri;
if (file_exists($root_target) && !is_dir($root_target)) {
    require $root_target;
    exit;
}

// Default Fallback to Homepage
require __DIR__ . '/../index.php';
