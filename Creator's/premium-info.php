<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Premium Membership - OnVerse</title>
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
          <a href="premium-info.php" class="nav-item premium-upsell active"
            ><i class="fa-solid fa-crown"></i><span>Upgrade Premium</span></a
          >
        </nav>
      </aside>

      <main class="main-content">
        <header class="main-header">
          <h1>Tingkatkan Pengalaman Membacamu</h1>
        </header>

        <div class="premium-benefits">
          <div class="benefit-item">
            <i class="fa-solid fa-book-journal-whills"></i>
            <h3>Baca Tanpa Batas</h3>
            <p>
              Akses semua episode premium dari karya mana pun tanpa perlu
              menggunakan koin.
            </p>
          </div>
          <div class="benefit-item">
            <i class="fa-solid fa-rocket"></i>
            <h3>Akses Lebih Awal</h3>
            <p>
              Nikmati episode terbaru 3 hari lebih cepat dari pengguna biasa.
            </p>
          </div>
          <div class="benefit-item">
            <i class="fa-solid fa-crown"></i>
            <h3>Lencana Premium</h3>
            <p>
              Tunjukkan dukunganmu dengan lencana eksklusif di profil dan
              komentarmu.
            </p>
          </div>
        </div>

        <div class="pricing-container">
          <h2>Pilih Paket Langgananmu</h2>
          <div class="pricing-grid">
            <div class="pricing-card">
              <h3>Bulanan</h3>
              <p class="price">Rp 29.000<span class="period">/bulan</span></p>
              <button class="btn btn-secondary full-width">Pilih Paket</button>
            </div>
            <div class="pricing-card popular">
              <span class="popular-badge">Paling Populer</span>
              <h3>Tahunan</h3>
              <p class="price">Rp 299.000<span class="period">/tahun</span></p>
              <p class="savings">Hemat 15%!</p>
              <button class="btn btn-primary full-width">Pilih Paket</button>
            </div>
            <div class="pricing-card">
              <h3>6 Bulan</h3>
              <p class="price">Rp 159.000<span class="period">/6 bln</span></p>
              <button class="btn btn-secondary full-width">Pilih Paket</button>
            </div>
          </div>
        </div>
      </main>
    </div>
    <div id="subscribeModal" class="modal-overlay" style="display: none">
      <div class="modal-content">
        <h3 class="modal-title">Konfirmasi Langganan</h3>
        <p>
          Anda akan berlangganan paket Tahunan seharga Rp 299.000. Lanjutkan ke
          pembayaran?
        </p>
        <div class="modal-actions">
          <button
            class="btn btn-secondary"
            onclick="closeModal('subscribeModal')"
          >
            Batal
          </button>
          <a href="subscribe.php" class="btn btn-primary">Lanjutkan</a>
        </div>
      </div>
    </div>
    <script src="script.js"></script>
  </body>
</html>