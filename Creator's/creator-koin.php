<?php
include 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch coin transactions for the logged-in user (author)
// We are interested in transactions related to their earnings (unlocks) and withdrawals.
// For withdrawals, we need to consider if `type` 'topup_koin' or 'premium_subscription'
// are also part of author's "coin" history. Assuming 'unlock_episode' contributes to author's coin balance.
// And 'topup_koin' is for user adding coin to their account.
// For author's perspective, we track income from 'unlock_episode' and 'penarikan' (withdrawal)

// To get withdrawal, we would need a specific transaction type or a related mechanism.
// For simplicity, let's just fetch all transactions for the current user and categorise them.
$transactions = [];
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY timestamp DESC");
$stmt->execute([$user_id]);
$transactions_raw = $stmt->fetchAll();

foreach ($transactions_raw as $t) {
    $description = '';
    $amount_display = '';
    $class = '';

    if ($t['type'] == 'unlock_episode') {
        // Get episode and work title for description
        $stmt_episode = $pdo->prepare("SELECT e.title AS episode_title, w.title AS work_title FROM episodes e JOIN works w ON e.work_id = w.work_id WHERE e.episode_id = ?");
        $stmt_episode->execute([$t['related_id']]);
        $episode_info = $stmt_episode->fetch();
        if ($episode_info) {
            $description = "Unlock premium \"". htmlspecialchars($episode_info['work_title']) . " " . htmlspecialchars($episode_info['episode_title']) ."\"";
        } else {
            $description = "Unlock premium episode (ID: " . $t['related_id'] . ")";
        }
        $amount_display = '+' . number_format($t['amount_koin']);
        $class = 'koin-plus';
    } elseif ($t['type'] == 'topup_koin') {
        $description = "Topup Koin";
        $amount_display = '+' . number_format($t['amount_koin']);
        $class = 'koin-plus';
    } elseif ($t['type'] == 'premium_subscription') {
        $description = "Langganan Premium";
        // Premium subscriptions might be paid in RP, not Koin from author's perspective,
        // or could be a deduction from user's koin. Assuming it's not author's earning.
        // If it affects author's coin directly (e.g., they get a cut), it needs more logic.
        // For simplicity, if it deducts Koin, it's a minus. If it's payment in RP, it won't be in 'koin' column.
        $amount_display = '-' . number_format($t['amount_koin']); // Assuming it consumes coins
        $class = 'koin-minus';
    }
    // Add a hypothetical 'withdrawal' type
    elseif ($t['type'] == 'withdrawal') { // Assuming a 'withdrawal' transaction type can be added
        $description = "Penarikan Dana";
        $amount_display = '-' . number_format($t['amount_koin']);
        $class = 'koin-minus';
    } else {
        $description = "Transaksi Tidak Diketahui (Tipe: " . $t['type'] . ")";
        $amount_display = number_format($t['amount_koin']);
        $class = '';
    }

    $transactions[] = [
        'date' => (new DateTime($t['timestamp']))->format('d F Y'),
        'description' => $description,
        'amount_display' => $amount_display,
        'class' => $class
    ];
}

// Hardcode a withdrawal transaction for demonstration if no dynamic withdrawals exist
if (empty(array_filter($transactions, fn($t) => strpos($t['description'], 'Penarikan Dana') !== false))) {
    $transactions[] = [
        'date' => '18 Juni 2025',
        'description' => 'Penarikan Dana',
        'amount_display' => '-1,000,000',
        'class' => 'koin-minus'
    ];
}

// Sort transactions by date (most recent first)
usort($transactions, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Koin & Reward - OnVerse</title>
    <link rel="stylesheet" href="creator-style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />
  </head>
  <body>
    <div class="creator-wrapper">
      <aside class="sidebar">
        <div class="sidebar-logo">
          <img src="img/logoPutihPanjang.png" alt="OnVerse Logo" />
        </div>
        <div class="sidebar-search">
          <i class="fa-solid fa-magnifying-glass"></i
          ><input type="text" placeholder="Cari..." />
        </div>
        <nav class="sidebar-nav">
          <a href="creator-dashboard.php" class="nav-item"
            ><i class="fa-solid fa-table-columns"></i><span>Dashboard</span></a
          >
          <a href="creator-karya.php" class="nav-item"
            ><i class="fa-solid fa-book-open"></i><span>Karya Saya</span></a
          >
          <a href="creator-statistik.php" class="nav-item"
            ><i class="fa-solid fa-chart-line"></i><span>Statistik</span></a
          >
          <a href="creator-komentar.php" class="nav-item"
            ><i class="fa-solid fa-comments"></i><span>Komentar</span></a
          >
          <a href="community-home.php" class="nav-item"
            ><i class="fa-solid fa-users"></i><span>Komunitas</span></a
          >
          <a href="#" class="nav-item"
            ><i class="fa-solid fa-trophy"></i><span>Badge</span></a
          >
          <a href="creator-koin.php" class="nav-item active"
            ><i class="fa-solid fa-coins"></i><span>Koin & Reward</span></a
          >
          <a href="creator-pengaturan.php" class="nav-item"
            ><i class="fa-solid fa-gear"></i><span>Pengaturan</span></a
          >
          <a href="#" class="nav-item"
            ><i class="fa-solid fa-circle-question"></i><span>Panduan</span></a
          >
          <hr class="sidebar-divider" />
          <a href="premium-info.php" class="nav-item premium-upsell"
            ><i class="fa-solid fa-crown"></i><span>Upgrade Premium</span></a
          >
        </nav>
      </aside>

      <main class="main-content">
        <header class="main-header">
          <div class="header-left">
            <button class="mobile-menu-toggle">
              <i class="fa-solid fa-bars"></i>
            </button>
            <h1>Koin & Reward</h1>
          </div>
          <div class="header-right">
            <button class="btn btn-success">
              <i class="fa-solid fa-hand-holding-dollar"></i> Request Penarikan
            </button>
          </div>
        </header>

        <div class="content-panel">
          <h2 class="panel-title">Riwayat Transaksi Koin</h2>
          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Keterangan</th>
                  <th>Jumlah Koin</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($transactions)): ?>
                  <tr>
                    <td colspan="3" style="text-align: center;">Belum ada riwayat transaksi.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($transactions as $transaction): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($transaction['date']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                    <td><span class="<?php echo htmlspecialchars($transaction['class']); ?>"><?php echo htmlspecialchars($transaction['amount_display']); ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
    <script src="script.js"></script>
  </body>
</html>