<?php
include 'db_connect.php';

$author_id = $_SESSION['user_id'];

// --- IMPORTANT: This section assumes a 'comments' table for episodes exists ---
// --- The onverse.sql provided does NOT have a table for comments on episodes. ---
// --- A table like `episode_comments` (comment_id, episode_id, user_id, content, created_at) would be needed. ---

// Hypothetical query for comments on author's episodes
// This query is illustrative and would work if an `episode_comments` table were present.
$comments = [];
try {
    $stmt = $pdo->prepare("
        SELECT
            ec.content,
            ec.created_at,
            u.username AS author_username,
            e.title AS episode_title,
            w.title AS work_title
        FROM
            episode_comments ec
        JOIN
            users u ON ec.user_id = u.id
        JOIN
            episodes e ON ec.episode_id = e.episode_id
        JOIN
            works w ON e.work_id = w.work_id
        WHERE
            w.author_id = ?
        ORDER BY
            ec.created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$author_id]);
    $comments = $stmt->fetchAll();
} catch (PDOException $e) {
    // If episode_comments table does not exist, this catch block will execute.
    // In a real application, you might log this error or show a user-friendly message.
    // For this demonstration, we'll just acknowledge it and keep comments static.
    $comments = []; // No dynamic comments if table is missing
    error_log("Database error fetching comments: " . $e->getMessage());
}

// Fallback to static comments if no dynamic data is fetched or table is missing
if (empty($comments)) {
    $comments = [
        [
            'author_username' => 'DepokSlayer',
            'work_title' => 'Pengabdi Janda',
            'episode_title' => 'Eps. 50',
            'content' => 'Endingnya gantung banget min, season 2 kapan rilis??',
            'created_at' => (new DateTime())->modify('-1 hour')->format('Y-m-d H:i:s')
        ],
        [
            'author_username' => 'AnakBaik',
            'work_title' => 'Pemuja Berhala',
            'episode_title' => 'Eps. 29',
            'content' => 'Serem banget ceritanya, jadi ga berani ke kamar mandi sendirian.',
            'created_at' => (new DateTime())->modify('-3 hours')->format('Y-m-d H:i:s')
        ],
    ];
}

// Function to format time difference
function time_ago($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' lalu' : 'baru saja';
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Komentar - OnVerse</title>
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
          <a href="creator-komentar.php" class="nav-item active"
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
            <h1>Komentar & Umpan Balik</h1>
          </div>
        </header>

        <div class="content-panel">
          <h2 class="panel-title">Komentar Terbaru</h2>
          <div class="comment-list">
            <?php if (empty($comments)): ?>
              <p>Belum ada komentar terbaru.</p>
            <?php else: ?>
              <?php foreach ($comments as $comment): ?>
              <div class="comment-item">
                <div class="comment-header">
                  <span class="comment-author"><?php echo htmlspecialchars($comment['author_username']); ?></span>
                  <span class="comment-meta"
                    >di <strong><?php echo htmlspecialchars($comment['work_title']); ?> <?php echo htmlspecialchars($comment['episode_title']); ?></strong> - <?php echo time_ago($comment['created_at']); ?></span
                  >
                </div>
                <p class="comment-body">
                  <?php echo htmlspecialchars($comment['content']); ?>
                </p>
                <div class="comment-actions">
                  <a href="#" class="btn-text btn-blue"
                    ><i class="fa-solid fa-reply"></i> Balas</a
                  >
                  <a href="#" class="btn-text btn-green"
                    ><i class="fa-solid fa-heart"></i> Tandai</a
                  >
                  <a href="#" class="btn-text btn-red"
                    ><i class="fa-solid fa-flag"></i> Laporkan</a
                  >
                </div>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </main>
    </div>
    <script src="script.js"></script>
  </body>
</html>