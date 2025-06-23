<?php
$active_page = 'dashboard';
$page_title = 'Admin Dashboard';
include 'includes/header.php';

// --- MENGAMBIL DATA STATISTIK ---
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalPremiumUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE is_premium = 1")->fetchColumn();
$totalCreators = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'author'")->fetchColumn();
$popularGenre = $pdo->query("SELECT genre, COUNT(*) as count FROM works GROUP BY genre ORDER BY count DESC LIMIT 1")->fetchColumn();
?>

<!-- Konten Dashboard -->
<section class="stats-grid">
    <div class="card">
        <p class="card-title">Total Users</p>
        <p class="card-value"><?php echo number_format($totalUsers); ?></p>
    </div>
    <div class="card">
        <p class="card-title">Premium Users</p>
        <p class="card-value"><?php echo number_format($totalPremiumUsers); ?></p>
    </div>
    <div class="card">
        <p class="card-title">Creators</p>
        <p class="card-value"><?php echo number_format($totalCreators); ?></p>
    </div>
    <div class="card">
        <p class="card-title">Popular Genre</p>
        <p class="card-value"><?php echo htmlspecialchars($popularGenre ?? 'N/A'); ?></p>
    </div>
</section>

<section class="admin-power">
    <h2>Admin's Power</h2>
    <div class="actions-grid">
        <a href="manage_user.php" class="action-button">Manage Users <i class="fa-solid fa-users-gear"></i></a>
        <a href="verify_creator.php" class="action-button">Verify Creators <i class="fa-solid fa-check-circle"
                style="color: #28a745;"></i></a>
        <a href="manage_content.php" class="action-button">Manage Contents <i class="fa-solid fa-file-alt"></i></a>
        <a href="manage_community.php" class="action-button">Manage Community <i class="fa-solid fa-comments"></i></a>
        <a href="manage_reports.php" class="action-button">Manage Reports <i class="fa-solid fa-triangle-exclamation"
                style="color: #dc3545;"></i></a>
        <a href="transaction.php" class="action-button">Transactions <i class="fa-solid fa-receipt"></i></a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>