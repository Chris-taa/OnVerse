<?php
// db_connect.php
$host = 'localhost'; // or your database host
$db   = 'onverse';
$user = 'root'; // your database username
$pass = ''; // your database password
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

// Start session and set a dummy user_id for demonstration
// In a real application, this would be set after successful login.
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Assuming Chris (id=1) is logged in
}
?>