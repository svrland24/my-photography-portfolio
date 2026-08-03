<?php
// ========================================================
// Dynamic CSS Endpoint for Vercel Serverless PHP
// ========================================================

header("Content-Type: text/css; charset=UTF-8");
header("Cache-Control: public, max-age=86400");

$css_file = __DIR__ . '/../assets/css/style.css';

if (file_exists($css_file)) {
    echo file_get_contents($css_file);
} else {
    echo "/* CSS stylesheet file not found */";
}
