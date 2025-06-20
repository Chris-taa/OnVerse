<?php
// db_connect.php

$host = 'localhost'; // Ganti jika host database Anda berbeda
$db   = 'onverse';    // Nama database Anda
$user = 'root';      // Username database Anda
$pass = '';          // Password database Anda (kosong jika tidak ada)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>