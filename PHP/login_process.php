<?php
// login_process.php
session_start(); // Mulai sesi untuk manajemen login dan pesan
require_once 'db_connect.php'; // Sertakan file koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form dan sanitasi
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validasi dasar server-side
    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = 'Email dan password harus diisi!';
        header('Location: login.html');
        exit();
    }

    // Ambil user dari database berdasarkan email/
    $stmt = $pdo->prepare("SELECT id, name, password FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Login berhasil
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['success_message'] = 'Login berhasil! Selamat datang, ' . $user['name'] . '!';
        header('Location: landing.html'); // Redirect ke halaman landing setelah login
        exit();
    } else {
        // Login gagal
        $_SESSION['error_message'] = 'Email atau password salah!';
        header('Location: login.html');
        exit();
    }
} else {
    // Jika bukan metode POST, redirect ke halaman login
    header('Location: login.html');
    exit();
}
?>