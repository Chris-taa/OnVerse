<?php
// signup_process.php
session_start(); // Mulai sesi untuk menyimpan pesan
require_once 'db_connect.php'; // Sertakan file koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form dan sanitasi
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $gender = htmlspecialchars(trim($_POST['gender']));
    $age = (int)$_POST['age'];
    $country = htmlspecialchars(trim($_POST['country']));
    $termsAccepted = isset($_POST['terms']); // Checkbox

    // Validasi dasar server-side (selain yang sudah di JS)
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($gender) || empty($age) || empty($country)) {
        $_SESSION['error_message'] = 'Semua kolom harus diisi!';
        header('Location: signup.html');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = 'Format email tidak valid!';
        header('Location: signup.html');
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error_message'] = 'Konfirmasi password tidak cocok!';
        header('Location: signup.html');
        exit();
    }

    if (!$termsAccepted) {
        $_SESSION['error_message'] = 'Anda harus menyetujui Syarat dan Ketentuan!';
        header('Location: signup.html');
        exit();
    }

    // Ubah gender menjadi format yang sesuai dengan ENUM database
    $genderMap = [
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other'
    ];
    $dbGender = $genderMap[$gender] ?? null;

    if ($dbGender === null) {
        $_SESSION['error_message'] = 'Pilihan gender tidak valid!';
        header('Location: signup.html');
        exit();
    }

    // Periksa apakah email sudah terdaftar
    $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error_message'] = 'Email ini sudah terdaftar!';
        header('Location: signup.html');
        exit();
    }

    // Hash password sebelum disimpan
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Siapkan query INSERT
    $sql = "INSERT INTO user (name, age, email, password, gender, country) VALUES (?, ?, ?, ?, ?, ?)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $age, $email, $hashedPassword, $dbGender, $country]);

        $_SESSION['success_message'] = 'Akun berhasil dibuat! Silakan login.';
        header('Location: login.html');
        exit();

    } catch (PDOException $e) {
        // Tangani error database
        $_SESSION['error_message'] = 'Terjadi kesalahan saat pendaftaran: ' . $e->getMessage();
        header('Location: signup.html');
        exit();
    }
} else {
    // Jika bukan metode POST, redirect ke halaman signup
    header('Location: signup.html');
    exit();
}
?>