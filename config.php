<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$DB_HOST = 'localhost';
$DB_NAME = 'book_site';
$DB_USER = 'root';
$DB_PASS = '';


$UPLOAD_DIR = __DIR__ . '/uploads';
$MAX_FILE_SIZE = 50 * 1024 * 1024; 

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>
