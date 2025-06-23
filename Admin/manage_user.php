<?php
$active_page = 'users';
$page_title = 'Manage Users';
include 'includes/header.php';

// Logika untuk menghapus user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $userIdToDelete = $_POST['delete_user_id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userIdToDelete]);
    header("Location: manage_user.php");
    exit;
}

// Mengambil semua data pengguna, termasuk 'fullname'
$stmt = $pdo->query("SELECT id, username, fullname, email, role, is_premium, verified FROM users ORDER BY id ASC");
$users = $stmt->fetchAll();
?>

<div class="content-panel">
    <!-- Menggunakan kelas 'user-grid-updated' untuk tata letak kolom baru -->
    <div class="data-grid user-grid-updated">
        <div class="grid-header">
            <span>ID</span>
            <span>Username</span>
            <span>Full Name</span>
            <span>Email</span>
            <span>Role</span>
            <span>Premium</span>
            <span>Verified</span>
            <span>Action</span>
        </div>

        <?php if (empty($users)): ?>
            <div class="grid-row-message">
                <p>Tidak ada data pengguna.</p>
            </div>
        <?php else: ?>
            <?php foreach ($users as $user): ?>
                <div class="grid-row">
                    <span data-label="ID"><?php echo htmlspecialchars($user['id']); ?></span>
                    <span data-label="Username"><?php echo htmlspecialchars($user['username']); ?></span>
                    <span data-label="Full Name"><?php echo htmlspecialchars($user['fullname'] ?? '-'); ?></span>
                    <span data-label="Email"><?php echo htmlspecialchars($user['email']); ?></span>
                    <span data-label="Role"><?php echo ucfirst(htmlspecialchars($user['role'])); ?></span>
                    <span data-label="Premium"><?php echo $user['is_premium'] ? 'Yes' : 'No'; ?></span>
                    <span data-label="Verified"><?php echo $user['verified'] ? 'Yes' : 'No'; ?></span>
                    <span data-label="Action">
                        <form method="POST" onsubmit="return confirm('Anda yakin ingin menghapus user ini?');"
                            style="display:inline;">
                            <input type="hidden" name="delete_user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="action-link delete">Delete</button>
                        </form>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>