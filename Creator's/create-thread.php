<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Buat Thread Baru - OnVerse</title>
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
            <a href="community-detail.php" class="btn btn-secondary"
              ><i class="fa-solid fa-arrow-left"></i> Kembali</a
            >
            <h1>Buat Thread Baru</h1>
          </div>
        </header>

        <div class="content-panel">
          <form class="create-thread-form">
            <div class="form-group">
              <label for="thread-title">Judul Thread</label>
              <input
                type="text"
                id="thread-title"
                placeholder="Apa yang ingin Anda diskusikan?"
              />
            </div>
            <div class="form-group">
              <label for="thread-content">Konten</label>
              <textarea
                id="thread-content"
                rows="10"
                placeholder="Tuliskan isi diskusimu di sini..."
              ></textarea>
            </div>
            <div class="form-group">
              <label for="thread-tags">Tag Karya (Opsional)</label>
              <input
                type="text"
                id="thread-tags"
                placeholder="Contoh: Pengabdi Janda, Pemuja Berhala"
              />
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">
                Publikasikan Thread
              </button>
              <button type="button" class="btn btn-secondary">
                Simpan sebagai Draft
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>
    <script src="script.js"></script>
  </body>
</html>