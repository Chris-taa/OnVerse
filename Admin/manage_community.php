<?php
$active_page = 'community';
$page_title = 'Manage Community Threads';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_thread_id'])) {
    $threadId = $_POST['delete_thread_id'];
    $stmt = $pdo->prepare("DELETE FROM community_threads WHERE thread_id = ?");
    $stmt->execute([$threadId]);
    header("Location: manage_community.php");
    exit;
}

$stmt = $pdo->query("
    SELECT t.thread_id, t.title, u.username as author, c.name as community_name
    FROM community_threads t
    JOIN users u ON t.user_id = u.id
    JOIN communities c ON t.community_id = c.community_id
    ORDER BY t.created_at DESC
");
$threads = $stmt->fetchAll();
?>

<div class="content-panel">
    <div class="data-grid community-grid">
        <div class="grid-header">
            <span>ID</span>
            <span>Judul Thread</span>
            <span>Author</span>
            <span>Komunitas</span>
            <span>Action</span>
        </div>

        <?php if (empty($threads)): ?>
            <div class="grid-row-message" style="text-align: center; padding: 1rem;">
                <p>Tidak ada thread di komunitas.</p>
            </div>
        <?php else: ?>
            <?php foreach ($threads as $thread): ?>
                <div class="grid-row">
                    <span data-label="ID"><?php echo htmlspecialchars($thread['thread_id']); ?></span>
                    <span data-label="Judul Thread"><?php echo htmlspecialchars($thread['title']); ?></span>
                    <span data-label="Author"><?php echo htmlspecialchars($thread['author']); ?></span>
                    <span data-label="Komunitas"><?php echo htmlspecialchars($thread['community_name']); ?></span>
                    <span data-label="Action">
                        <form method="POST" onsubmit="return confirm('Hapus thread ini secara permanen?');"
                            style="display:inline;">
                            <input type="hidden" name="delete_thread_id" value="<?php echo $thread['thread_id']; ?>">
                            <button type="submit" class="action-link delete">Delete</button>
                        </form>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>