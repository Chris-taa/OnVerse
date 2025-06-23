<?php
$active_page = 'transactions';
$page_title = 'Transactions';
include 'includes/header.php';

$stmt = $pdo->query("
    SELECT t.transaction_id, u.username, t.amount_koin, t.type, t.timestamp
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    ORDER BY t.timestamp DESC
    LIMIT 50
");
$transactions = $stmt->fetchAll();
?>

<div class="content-panel">
    <div class="data-grid transaction-grid">
        <div class="grid-header">
            <span>ID</span>
            <span>Username</span>
            <span>Tipe</span>
            <span>Jumlah</span>
            <span>Tanggal</span>
        </div>

        <?php foreach ($transactions as $trx): ?>
            <div class="grid-row">
                <span data-label="ID"><?php echo htmlspecialchars($trx['transaction_id']); ?></span>
                <span data-label="Username"><?php echo htmlspecialchars($trx['username']); ?></span>
                <span data-label="Tipe">
                    <?php if ($trx['type'] == 'topup_koin'): ?>
                        <span class="koin-plus">Top-up</span>
                    <?php elseif ($trx['type'] == 'unlock_episode'): ?>
                        <span class="koin-minus">Unlock</span>
                    <?php else: ?>
                        <span>Premium</span>
                    <?php endif; ?>
                </span>
                <span data-label="Jumlah">
                    <?php if ($trx['type'] == 'topup_koin'): ?>
                        <span class="koin-plus">+<?php echo number_format($trx['amount_koin']); ?></span>
                    <?php else: ?>
                        <span class="koin-minus">-<?php echo number_format($trx['amount_koin']); ?></span>
                    <?php endif; ?>
                </span>
                <span data-label="Tanggal"><?php echo date('d M Y, H:i', strtotime($trx['timestamp'])); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>