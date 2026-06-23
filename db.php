<?php
// Database connection (edit credentials as needed)
session_start();
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'butter_fashion');
define('DB_USER', 'root');
define('DB_PASS', '');
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // Show a helpful message for local development
    die("Database connection failed: " . $e->getMessage() . "\n\nIf this is the first run, please run setup.php to create the database and sample users.");
}
