<?php
$active_page = 'reports';
$page_title = 'Manage Reports';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_report_id'])) {
    $reportId = $_POST['resolve_report_id'];
    $stmt = $pdo->prepare("UPDATE reports SET status = 'resolved' WHERE report_id = ?");
    $stmt->execute([$reportId]);
    header("Location: manage_reports.php");
    exit;
}

// Query diperbaiki untuk mengambil data pelapor dan terlapor dengan benar
$stmt = $pdo->query("
    SELECT 
        r.report_id, 
        r.reason, 
        r.status, 
        u_reporter.username AS reporter_username,
        u_reported.username AS reported_username
    FROM reports AS r
    LEFT JOIN users AS u_reporter ON r.reporter_id = u_reporter.id
    LEFT JOIN users AS u_reported ON r.reported_content_id = u_reported.id AND r.reported_content_type = 'user'
    WHERE r.status = 'pending'
    ORDER BY r.report_id ASC
");
$reports = $stmt->fetchAll();
?>

<div class="content-panel">
    <div class="data-grid reports-grid">
        <div class="grid-header">
            <span>ID</span>
            <span>Pelapor</span>
            <span>Terlapor</span>
            <span>Alasan</span>
            <span>Action</span>
        </div>

        <?php if (empty($reports)): ?>
            <div class="grid-row-message" style="text-align: center; padding: 1rem;">
                <p>Tidak ada laporan yang tertunda.</p>
            </div>
        <?php else: ?>
            <?php foreach ($reports as $report): ?>
                <div class="grid-row">
                    <span data-label="ID"><?php echo htmlspecialchars($report['report_id']); ?></span>
                    <span data-label="Pelapor"><?php echo htmlspecialchars($report['reporter_username']); ?></span>
                    <span data-label="Terlapor"><?php echo htmlspecialchars($report['reported_username'] ?? 'Konten'); ?></span>
                    <span data-label="Alasan"><?php echo nl2br(htmlspecialchars($report['reason'])); ?></span>
                    <span data-label="Action">
                        <form method="POST" onsubmit="return confirm('Tandai laporan ini sebagai selesai?');"
                            style="display:inline;">
                            <input type="hidden" name="resolve_report_id" value="<?php echo $report['report_id']; ?>">
                            <button type="submit" class="action-link accept">Resolve</button>
                        </form>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>