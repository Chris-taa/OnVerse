<?php
$active_page = 'content';
$page_title = 'Manage Contents';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deactivate_work_id'])) {
    $workId = $_POST['deactivate_work_id'];
    $stmt = $pdo->prepare("UPDATE works SET status = 'hidden' WHERE work_id = ?");
    $stmt->execute([$workId]);
    header("Location: manage_content.php");
    exit;
}

// Query diperbarui untuk mengambil 'genre' dan 'genre2'
$stmt = $pdo->query("
    SELECT w.work_id, w.title, w.genre, w.genre2, w.status, u.username as author_name
    FROM works w
    JOIN users u ON w.author_id = u.id
    ORDER BY w.work_id ASC
");
$works = $stmt->fetchAll();
?>

<div class="content-panel">
    <div class="data-grid content-grid-updated">
        <div class="grid-header">
            <span>ID</span>
            <span>Title</span>
            <span>Author</span>
            <span>Genre 1</span>
            <span>Genre 2</span>
            <span>Status</span>
            <span>Action</span>
        </div>

        <?php foreach ($works as $work): ?>
            <div class="grid-row">
                <span data-label="ID"><?php echo htmlspecialchars($work['work_id']); ?></span>
                <span data-label="Title"><?php echo htmlspecialchars($work['title']); ?></span>
                <span data-label="Author"><?php echo htmlspecialchars($work['author_name']); ?></span>
                <span data-label="Genre 1"><?php echo htmlspecialchars($work['genre']); ?></span>
                <span data-label="Genre 2"><?php echo htmlspecialchars($work['genre2'] ?? '-'); ?></span>
                <span data-label="Status"><span
                        class="status-tag <?php echo htmlspecialchars($work['status']); ?>"><?php echo ucfirst(htmlspecialchars($work['status'])); ?></span></span>
                <span data-label="Action">
                    <form method="POST" onsubmit="return confirm('Anda yakin ingin menonaktifkan konten ini?');"
                        style="display:inline;">
                        <input type="hidden" name="deactivate_work_id" value="<?php echo $work['work_id']; ?>">
                        <button type="submit" class="action-link delete">Deactivate</button>
                    </form>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>