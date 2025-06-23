<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Komunitas - OnVerse</title>
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
          <a href="community-home.php" class="nav-item active"
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
            <h1>Jelajahi Komunitas</h1>
          </div>
          <div class="header-right">
            <div class="creator-info">
              <span class="creator-name">Raja Cakra</span>
              <span class="verification-status verified"
                ><i class="fa-solid fa-check-circle"></i> Terverifikasi</span
              >
            </div>
          </div>
        </header>

        <div class="content-panel">
          <div class="filter-bar">
            <input
              type="text"
              placeholder="Cari komunitas..."
              class="filter-search"
            />
            <select class="filter-select">
              <option>Semua Genre</option>
              <option>Action</option>
              <option>Romance</option>
              <option>Horror</option>
            </select>
          </div>

          <div class="community-grid">
            <div class="community-card">
              <div
                class="card-header"
                style="
                  background-image: url('https://placehold.co/600x200/2563EB/FFFFFF?text=Banner');
                "
              >
                <img
                  src="https://placehold.co/80x80"
                  alt="Avatar Komunitas"
                  class="card-avatar"
                />
              </div>
              <div class="card-body">
                <h3>Komunitas Pengabdi Janda</h3>
                <p class="card-members">
                  <i class="fa-solid fa-users"></i> 1,200 Anggota
                </p>
                <p class="card-description">
                  Grup diskusi resmi untuk para pembaca setia novel "Pengabdi
                  Janda".
                </p>
                <a
                  href="community-detail.php"
                  class="btn btn-primary full-width"
                  >Kunjungi</a
                >
              </div>
            </div>
            <div class="community-card">
              <div
                class="card-header"
                style="
                  background-image: url('https://placehold.co/600x200/ef4444/FFFFFF?text=Banner');
                "
              >
                <img
                  src="https://placehold.co/80x80"
                  alt="Avatar Komunitas"
                  class="card-avatar"
                />
              </div>
              <div class="card-body">
                <h3>Fans Pemuja Berhala</h3>
                <p class="card-members">
                  <i class="fa-solid fa-users"></i> 850 Anggota
                </p>
                <p class="card-description">
                  Tempatnya para pembaca pemberani yang suka dengan genre horor.
                </p>
                <a href="#" class="btn btn-primary full-width">Kunjungi</a>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    <script src="script.js"></script>
  </body>
</html>