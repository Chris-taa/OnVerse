<?php
include 'db_connect.php';

$author_id = $_SESSION['user_id'];

// Get the current date for calculating date ranges
$current_date = new DateTime();
$thirty_days_ago = (clone $current_date)->modify('-30 days')->format('Y-m-d H:i:s');
$seven_days_ago = (clone $current_date)->modify('-7 days')->format('Y-m-d H:i:s');
$ninety_days_ago = (clone $current_date)->modify('-90 days')->format('Y-m-d H:i:s');

// Fetch Total Unlock (30 Hari) for the author's works
$stmt = $pdo->prepare("
    SELECT COUNT(t.transaction_id) AS total_unlocks
    FROM transactions t
    JOIN episodes e ON t.related_id = e.episode_id
    JOIN works w ON e.work_id = w.work_id
    WHERE t.type = 'unlock_episode'
    AND w.author_id = ?
    AND t.timestamp >= ?
    AND t.status = 'completed'
");
$stmt->execute([$author_id, $thirty_days_ago]);
$total_unlocks_30_days = $stmt->fetch()['total_unlocks'] ?? 0;

// Fetch Koin Dihasilkan (30 Hari) for the author's works
$stmt = $pdo->prepare("
    SELECT SUM(t.amount_koin) AS total_coins_generated
    FROM transactions t
    JOIN episodes e ON t.related_id = e.episode_id
    JOIN works w ON e.work_id = w.work_id
    WHERE t.type = 'unlock_episode'
    AND w.author_id = ?
    AND t.timestamp >= ?
    AND t.status = 'completed'
");
$stmt->execute([$author_id, $thirty_days_ago]);
$total_coins_generated_30_days = $stmt->fetch()['total_coins_generated'] ?? 0;

// Fetch Rata-rata Rating (jika tabel rating tersedia)
// Ini adalah contoh - Anda perlu membuat tabel ratings terlebih dahulu
try {
    $stmt = $pdo->prepare("
        SELECT AVG(r.rating_value) AS average_rating
        FROM ratings r
        JOIN episodes e ON r.episode_id = e.episode_id
        JOIN works w ON e.work_id = w.work_id
        WHERE w.author_id = ?
    ");
    $stmt->execute([$author_id]);
    $average_rating = $stmt->fetch()['average_rating'];
} catch (PDOException $e) {
    $average_rating = 4.8; // Default jika tabel rating tidak ada
}

// Fetch list of works for the filter dropdown
$stmt = $pdo->prepare("SELECT work_id, title FROM works WHERE author_id = ? ORDER BY title ASC");
$stmt->execute([$author_id]);
$authors_works = $stmt->fetchAll();

// Fetch statistik per episode (contoh untuk karya pertama)
$first_work_id = $authors_works[0]['work_id'] ?? null;

if ($first_work_id) {
    $stmt = $pdo->prepare("
        SELECT 
            e.episode_id,
            CONCAT('Chapter ', e.episode_number, ': ', e.title) AS episode_title,
            COUNT(DISTINCT t.user_id) AS total_readers,
            COUNT(t.transaction_id) AS total_unlocks,
            AVG(r.rating_value) AS average_rating,
            COUNT(c.comment_id) AS total_comments
        FROM episodes e
        LEFT JOIN transactions t ON t.related_id = e.episode_id AND t.type = 'unlock_episode' AND t.status = 'completed'
        LEFT JOIN ratings r ON r.episode_id = e.episode_id
        LEFT JOIN comments c ON c.episode_id = e.episode_id
        WHERE e.work_id = ?
        GROUP BY e.episode_id
        ORDER BY e.episode_number DESC
        LIMIT 5
    ");
    $stmt->execute([$first_work_id]);
    $episode_stats = $stmt->fetchAll();
} else {
    $episode_stats = [];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Statistik Karya - OnVerse</title>
  <link rel="stylesheet" href="creator-style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
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
          <a href="creator-koin.php" class="nav-item"
            ><i class="fa-solid fa-coins"></i><span>Koin & Reward</span></a
          >
          <a href="creator-pengaturan.php" class="nav-item active"
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
          <h1>Statistik Karya</h1>
        </div>
        <div class="header-right">
          <button class="btn btn-secondary">
            <i class="fa-solid fa-download"></i> Export Laporan
          </button>
        </div>
      </header>

      <section class="stats-grid-creator">
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fa-solid fa-unlock-keyhole"></i>
          </div>
          <div class="stat-info">
            <p>Total Unlock (30 Hari)</p>
            <span><?php echo number_format($total_unlocks_30_days); ?></span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fa-solid fa-star-half-alt"></i>
          </div>
          <div class="stat-info">
            <p>Rata-rata Rating</p>
            <span class="rating-stars"><?php echo number_format($average_rating, 1); ?> <i
                class="fa-solid fa-star"></i></span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon"><i class="fa-solid fa-coins"></i></div>
          <div class="stat-info">
            <p>Koin Dihasilkan (30 Hari)</p>
            <span><?php echo number_format($total_coins_generated_30_days); ?></span>
          </div>
        </div>
      </section>

      <div class="content-panel">
        <div class="filter-bar">
          <div class="form-group">
            <label for="pilih-karya">Pilih Karya</label>
            <select id="pilih-karya">
              <option value="all">Semua Karya</option>
              <?php foreach ($authors_works as $work): ?>
                <option value="<?php echo $work['work_id']; ?>"><?php echo htmlspecialchars($work['title']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="rentang-waktu">Rentang Waktu</label>
            <select id="rentang-waktu">
              <option value="30">30 Hari Terakhir</option>
              <option value="7">7 Hari Terakhir</option>
              <option value="90">90 Hari Terakhir</option>
            </select>
          </div>
        </div>
        <div class="chart-placeholder">
          <img src="https://placehold.co/1000x400/EBF2FF/0038B4?text=Grafik+Jumlah+Pembaca" alt="Grafik Statistik"
            style="width: 100%; height: auto; border-radius: 8px" />
        </div>
      </div>

      <div class="content-panel" style="margin-top: 1.5rem">
        <h2 class="panel-title">Statistik per Episode</h2>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>Episode</th>
                <th>Pembaca</th>
                <th>Unlock</th>
                <th>Rating</th>
                <th>Komentar</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($episode_stats)): ?>
                <?php foreach ($episode_stats as $stat): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($stat['episode_title'] ?? 'N/A'); ?></td>
                    <td><?php echo number_format($stat['total_readers'] ?? 0); ?></td>
                    <td><?php echo number_format($stat['total_unlocks'] ?? 0); ?></td>
                    <td>
                      <span class="rating-stars"><?php echo number_format($stat['average_rating'] ?? 0, 1); ?> <i
                          class="fa-solid fa-star"></i></span>
                    </td>
                    <td><?php echo number_format($stat['total_comments'] ?? 0); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" style="text-align: center;">Tidak ada data episode</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
  <script src="script.js"></script>
  <script>
    // JavaScript untuk filter
    document.getElementById('pilih-karya').addEventListener('change', function() {
      const workId = this.value;
      // AJAX request untuk memuat data berdasarkan karya yang dipilih
      console.log('Memuat data untuk karya ID:', workId);
      // Implementasi AJAX bisa ditambahkan di sini
    });

    document.getElementById('rentang-waktu').addEventListener('change', function() {
      const days = this.value;
      // AJAX request untuk memuat data berdasarkan rentang waktu
      console.log('Memuat data untuk', days, 'hari terakhir');
      // Implementasi AJAX bisa ditambahkan di sini
    });
  </script>
</body>

</html>