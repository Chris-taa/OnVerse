<?php
include 'db_connect.php';

$author_id = $_SESSION['user_id']; // Get the logged-in author's ID

// Fetch author information
$stmt = $pdo->prepare("SELECT username, verified FROM users WHERE id = ?");
$stmt->execute([$author_id]);
$author_info = $stmt->fetch();

$creator_name = $author_info['username'] ?? 'N/A';
$is_verified = $author_info['verified'] ?? 0;

// Fetch Total Karya
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_works FROM works WHERE author_id = ?");
$stmt->execute([$author_id]);
$total_works = $stmt->fetch()['total_works'] ?? 0;

// Fetch Total Episode
$stmt = $pdo->prepare("SELECT COUNT(e.episode_id) AS total_episodes
                       FROM episodes e
                       JOIN works w ON e.work_id = w.work_id
                       WHERE w.author_id = ?");
$stmt->execute([$author_id]);
$total_episodes = $stmt->fetch()['total_episodes'] ?? 0;

// Fetch Total Koin Didapat (from transactions related to this author's works)
// This is a simplification. A more accurate calculation might involve tracking income per unlock.
// For now, let's sum up 'unlock_episode' transactions.
$stmt = $pdo->prepare("SELECT SUM(t.amount_koin) AS total_coins_earned
                       FROM transactions t
                       JOIN episodes e ON t.related_id = e.episode_id
                       JOIN works w ON e.work_id = w.work_id
                       WHERE t.type = 'unlock_episode' AND w.author_id = ? AND t.status = 'completed'");
$stmt->execute([$author_id]);
$total_coins_earned = $stmt->fetch()['total_coins_earned'] ?? 0;

// --- Data that remains static or requires more complex logic/tables ---
// Jumlah Pembaca: Requires a more complex query to count unique users who unlocked episodes from this author.
// Karya Terpopuler: Requires aggregation of unlocks/ratings per work.
// Notifikasi Terbaru: Requires a dedicated 'notifications' table.
$jumlah_pembaca = '15,7K'; // Static for now
$karya_terpopuler = 'Pengabdi Janda'; // Static for now
$notifications = [
    ['icon' => 'fa-comment', 'text' => '<strong>DepokSlayer</strong> mengomentari Episode 24.'],
    ['icon' => 'fa-heart', 'text' => '<strong>Pemuja Berhala</strong> disukai 100+ pengguna.'],
    ['icon' => 'fa-shield-halved', 'text' => 'Anda mendapat badge <strong>Top Rated Novel</strong>!'],
];
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Creator Dashboard - OnVerse</title>
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
        <nav class="sidebar-nav">
          <a href="creator-dashboard.php" class="nav-item active"
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
          <a href="creator-koin.php" class="nav-item"
            ><i class="fa-solid fa-coins"></i><span>Koin & Reward</span></a
          >
          <a href="creator-pengaturan.php" class="nav-item"
            ><i class="fa-solid fa-gear"></i><span>Pengaturan</span></a
          >
          <a href="#" class="nav-item"
            ><i class="fa-solid fa-circle-question"></i><span>Panduan</span></a
          >
          <a href="../HTML/landing.php" class = "nav-item">
            <i class="fa-solid fa-circle-question"></i><span>Return</span>
          </a>
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
            <h1>Dashboard</h1>
          </div>
          <div class="header-right">
            <div class="creator-info">
              <span class="creator-name"><?php echo htmlspecialchars($creator_name); ?></span>
              <span class="verification-status <?php echo $is_verified ? 'verified' : ''; ?>">
                <i class="fa-solid fa-check-circle"></i> <?php echo $is_verified ? 'Terverifikasi' : 'Belum Terverifikasi'; ?>
              </span>
            </div>
            <div class="notification-icon">
              <i class="fa-solid fa-bell"></i>
              <span class="notification-badge">3</span>
            </div>
          </div>
        </header>

        <section class="stats-grid-creator">
          <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-book"></i></div>
            <div class="stat-info">
              <p>Total Karya</p>
              <span><?php echo $total_works; ?></span>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-file-lines"></i></div>
            <div class="stat-info">
              <p>Total Episode</p>
              <span><?php echo $total_episodes; ?></span>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
            <div class="stat-info">
              <p>Jumlah Pembaca</p>
              <span><?php echo $jumlah_pembaca; ?></span>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-star"></i></div>
            <div class="stat-info">
              <p>Karya Terpopuler</p>
              <span><?php echo htmlspecialchars($karya_terpopuler); ?></span>
            </div>
          </div>
        </section>

        <div class="content-row">
          <div class="content-panel half-width">
            <h2 class="panel-title">Notifikasi Terbaru</h2>
            <ul class="notification-list">
              <?php foreach ($notifications as $notification): ?>
                <li>
                  <i class="fa-solid <?php echo htmlspecialchars($notification['icon']); ?>"></i>
                  <?php echo $notification['text']; ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="content-panel half-width">
            <h2 class="panel-title">Ringkasan Penghasilan</h2>
            <div class="koin-summary">
              <i class="fa-solid fa-coins"></i>
              <div class="koin-details">
                <p>Total Koin Didapat</p>
                <span><?php echo number_format($total_coins_earned); ?></span>
              </div>
            </div>
            <a href="creator-koin.php" class="btn btn-primary"
              >Lihat Riwayat Koin</a
            >
          </div>
        </div>
      </main>
    </div>

    <script src="script.js"></script>
  </body>
</html>