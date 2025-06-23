<?php
$active_page = 'verify';
$page_title = 'Verify Creators';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verify_user_id'])) {
        $userId = $_POST['verify_user_id'];
        $stmt = $pdo->prepare("UPDATE users SET verified = 1 WHERE id = ?");
        $stmt->execute([$userId]);
    }
    if (isset($_POST['reject_user_id'])) {
        $userId = $_POST['reject_user_id'];
        $stmt = $pdo->prepare("UPDATE users SET role = 'reader', creator = 0 WHERE id = ?");
        $stmt->execute([$userId]);
    }
    header("Location: verify_creator.php");
    exit;
}

$stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE role = 'author' AND verified = 0");
$stmt->execute();
$unverifiedCreators = $stmt->fetchAll();
?>

<div class="content-panel">
    <div class="data-grid verify-grid">
        <div class="grid-header">
            <span>ID</span>
            <span>Username</span>
            <span>Email</span>
            <span class="action-column">Action</span>
        </div>

        <?php if (empty($unverifiedCreators)): ?>
            <div class="grid-row-message" style="text-align: center; padding: 1rem;">
                <p>Tidak ada kreator yang perlu diverifikasi.</p>
            </div>
        <?php else: ?>
            <?php foreach ($unverifiedCreators as $creator): ?>
                <div class="grid-row">
                    <span data-label="ID"><?php echo htmlspecialchars($creator['id']); ?></span>
                    <span data-label="Username"><?php echo htmlspecialchars($creator['username']); ?></span>
                    <span data-label="Email"><?php echo htmlspecialchars($creator['email']); ?></span>
                    <span data-label="Action" class="action-column">
                        <form method="POST" onsubmit="return confirm('Verifikasi kreator ini?');" style="display:inline;">
                            <input type="hidden" name="verify_user_id" value="<?php echo $creator['id']; ?>">
                            <button type="submit" class="action-link accept">Verify</button>
                        </form>
                        <form method="POST" onsubmit="return confirm('Tolak permintaan kreator ini?');" style="display:inline;">
                            <input type="hidden" name="reject_user_id" value="<?php echo $creator['id']; ?>">
                            <button type="submit" class="action-link reject">Reject</button>
                        </form>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>