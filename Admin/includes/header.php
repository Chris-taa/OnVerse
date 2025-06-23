<?php
// Memanggil config.php dari direktori root Admin
// Pastikan config.php ada di dalam folder Admin/
require_once __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Panel'; ?> - OnVerse</title>

    <!-- Tautan CSS menggunakan BASE_URL yang sudah didefinisikan di config.php -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <div class="admin-wrapper">
        <?php include 'sidebar.php'; // Memuat sidebar ?>
        <!-- Konten utama dimulai di sini -->
        <div class="main-content">
            <header class="main-header">
                <div class="header-left">
                    <button class="mobile-menu-toggle"><i class="fa-solid fa-bars"></i></button>
                    <!-- Judul Halaman dinamis -->
                    <h1 class="page-main-title"><?php echo $page_title ?? 'Dashboard'; ?></h1>
                </div>
                <div class="header-right">
                    <div class="admin-info">
                        <span class="admin-name">Admin OnVerse</span>
                        <span class="admin-role">
                            <i class="fa-solid fa-user-shield"></i> Super Admin
                        </span>
                    </div>
                </div>
            </header>
            <main class="page-content">