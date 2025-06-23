<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Berlangganan Premium - OnVerse</title>
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
          <h1>Selesaikan Pembayaran</h1>
        </header>

        <div class="checkout-layout">
          <div class="checkout-form-container">
            <div class="content-panel">
              <h3>Pilih Metode Pembayaran</h3>
              <div class="payment-methods">
                <button class="payment-method active">Virtual Account</button>
                <button class="payment-method">Kartu Kredit</button>
                <button class="payment-method">E-Wallet</button>
              </div>
              <form class="payment-form">
                <div class="form-group">
                  <label for="email">Alamat Email</label>
                  <input
                    type="email"
                    id="email"
                    value="rajacakra@email.com"
                    readonly
                  />
                </div>
                <div class="form-group">
                  <label for="card-holder">Nama Pemegang Kartu</label>
                  <input
                    type="text"
                    id="card-holder"
                    placeholder="Nama sesuai kartu"
                  />
                </div>
                <div class="form-group">
                  <label for="card-number">Nomor Kartu</label>
                  <input
                    type="text"
                    id="card-number"
                    placeholder="0000 0000 0000 0000"
                  />
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label for="expiry-date">Tanggal Kedaluwarsa</label>
                    <input type="text" id="expiry-date" placeholder="MM/YY" />
                  </div>
                  <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" placeholder="123" />
                  </div>
                </div>
              </form>
            </div>
          </div>

          <div class="checkout-summary-container">
            <div class="content-panel">
              <h3>Ringkasan Langganan</h3>
              <div class="summary-item">
                <span>Paket Tahunan</span>
                <span>Rp 299.000</span>
              </div>
              <div class="summary-item">
                <span>Diskon</span>
                <span class="text-green">- Rp 52.000</span>
              </div>
              <div class="summary-item total">
                <span>Total</span>
                <span>Rp 299.000</span>
              </div>
              <p class="terms">
                Dengan melanjutkan, Anda menyetujui
                <strong>Syarat & Ketentuan</strong> kami.
              </p>
              <button class="btn btn-primary full-width">Bayar Sekarang</button>
            </div>
          </div>
        </div>
      </main>
    </div>
    <script src="script.js"></script>
  </body>
</html>