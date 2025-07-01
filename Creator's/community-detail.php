<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Komunitas - OnVerse</title>
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
        <i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Cari..." />
      </div>
      <nav class="sidebar-nav">
        <a href="creator-dashboard.php" class="nav-item"><i
            class="fa-solid fa-table-columns"></i><span>Dashboard</span></a>
        <a href="creator-karya.php" class="nav-item"><i class="fa-solid fa-book-open"></i><span>Karya Saya</span></a>
        <a href="creator-statistik.php" class="nav-item"><i
            class="fa-solid fa-chart-line"></i><span>Statistik</span></a>
        <a href="creator-komentar.php" class="nav-item"><i class="fa-solid fa-comments"></i><span>Komentar</span></a>
        <a href="community-home.php" class="nav-item active"><i class="fa-solid fa-users"></i><span>Komunitas</span></a>
        <a href="#" class="nav-item"><i class="fa-solid fa-trophy"></i><span>Badge</span></a>
        <a href="creator-koin.php" class="nav-item"><i class="fa-solid fa-coins"></i><span>Koin & Reward</span></a>
        <a href="creator-pengaturan.php" class="nav-item"><i class="fa-solid fa-gear"></i><span>Pengaturan</span></a>
        <a href="#" class="nav-item"><i class="fa-solid fa-circle-question"></i><span>Panduan</span></a>
        <hr class="sidebar-divider" />
        <a href="premium-info.php" class="nav-item premium-upsell"><i class="fa-solid fa-crown"></i><span>Upgrade
            Premium</span></a>
      </nav>
    </aside>

    <main class="main-content">
      <header class="community-page-header" style="
            background-image: url('https://placehold.co/1200x300/2563EB/FFFFFF?text=Community+Banner');
          ">
        <div class="header-overlay">
          <h1>Komunitas Pengabdi Janda</h1>
          <p>1,200 Anggota</p>
          <button class="btn btn-success">
            <i class="fa-solid fa-plus"></i> Gabung Komunitas
          </button>
        </div>
      </header>

      <div class="content-panel">
        <div class="community-tabs">
          <button class="tab-item active">Diskusi Umum</button>
          <button class="tab-item">Pengumuman Kreator</button>
        </div>

        <div class="community-content">
          <div class="thread-list">
            <div class="thread-actions">
              <h3>Diskusi Terbaru</h3>
              <a href="create-thread.php" class="btn btn-primary">
                <i class="fa-solid fa-pen-to-square"></i> Buat Thread Baru
              </a>
            </div>
            <a href="community-thread.php" class="thread-item">
              <div class="thread-main">
                <span class="thread-title">Teori Ending Season 2, Setuju Gak?</span>
                <span class="thread-meta">oleh <strong>DepokSlayer</strong> • 2 jam lalu</span>
              </div>
              <div class="thread-stats">
                <span title="Balasan"><i class="fa-solid fa-comments"></i> 15</span>
                <span title="Likes"><i class="fa-solid fa-thumbs-up"></i> 45</span>
              </div>
            </a>
            <a href="#" class="thread-item">
              <div class="thread-main">
                <span class="thread-title">Karakter Favorit Kalian Siapa?</span>
                <span class="thread-meta">oleh <strong>AnakBaik</strong> • 5 jam lalu</span>
              </div>
              <div class="thread-stats">
                <span title="Balasan"><i class="fa-solid fa-comments"></i> 32</span>
                <span title="Likes"><i class="fa-solid fa-thumbs-up"></i> 88</span>
              </div>
            </a>
          </div>
        </div>
      </div>
    </main>
  </div>
  <div id="joinCommunityModal" class="modal-overlay" style="display: none">
    <div class="modal-content">
      <h3 class="modal-title">Konfirmasi</h3>
      <p>
        Yakin ingin bergabung dengan komunitas "Komunitas Pengabdi Janda"?
      </p>
      <div class="modal-actions">
        <button class="btn btn-secondary" onclick="closeModal('joinCommunityModal')">
          Batal
        </button>
        <button class="btn btn-primary">Ya, Bergabung</button>
      </div>
    </div>
  </div>
  <script src="script.js"></script>
</body>

</html>