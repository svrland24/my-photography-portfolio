<?php
// ========================================================
// Photography Portfolio Configuration & Database Connection
// ========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = null;
$db_error = null;

// --------------------------------------------------------
// DATABASE MODE SELECTION: Set to 'supabase'
// --------------------------------------------------------
$db_mode = 'supabase'; 

// ========================================================
// 🟢 SUPABASE CLOUD CONFIGURATION (Tokyo Pooler - ACTIVE)
// ========================================================
$supabase_host     = 'aws-0-ap-northeast-1.pooler.supabase.com'; // Tokyo Pooler Host
$supabase_port     = '6543';                                     // Pooler Port
$supabase_db       = 'postgres';                                 // Database name
$supabase_user     = 'postgres.xikfgjbhfrsctzpzartc';           // User with project ref
$supabase_password = 'Shojol@#$1122';                            // Password

// ========================================================
// 🟡 XAMPP MYSQL CONFIGURATION (Local Machine Fallback)
// ========================================================
$mysql_host = '127.0.0.1';
$mysql_user = 'root';
$mysql_pass = '';
$mysql_name = 'photography_db';

try {
    if ($db_mode === 'supabase') {
        // Connect to Supabase Cloud Database via PDO
        $dsn = "pgsql:host=$supabase_host;port=$supabase_port;dbname=$supabase_db;sslmode=require";
        $pdo = new PDO($dsn, $supabase_user, $supabase_password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 5
        ]);
    } else {
        // Local XAMPP MySQL Connection
        $pdo = new PDO("mysql:host=$mysql_host;charset=utf8mb4", $mysql_user, $mysql_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$mysql_name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$mysql_name`");
    }
} catch (PDOException $e) {
    $db_error = $e->getMessage();
    $pdo = null;
}

// Clean SITE_URL definition for Vercel & Localhost without /api/ path contamination
$scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('SITE_URL', $scheme . '://' . $host);

define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', 'uploads/');
