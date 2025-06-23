<?php
// Memulai session untuk manajemen login
session_start();

// --- DEFINISI URL DASAR ---
// Ini akan secara otomatis mendeteksi URL root Anda (http://localhost/Admin)
define('BASE_URL', sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    dirname($_SERVER['PHP_SELF']) === DIRECTORY_SEPARATOR ? '' : dirname($_SERVER['PHP_SELF'])
));


// --- PENGATURAN KONEKSI DATABASE ---
$host = 'localhost';
$db = 'onverse';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

// Fungsi untuk memeriksa apakah admin sudah login
function isAdminLoggedIn()
{
    return true; // Asumsi selalu login untuk pengembangan
}

if (!isAdminLoggedIn()) {
    // header('Location: ' . BASE_URL . '/login.php');
    // exit;
}
?>