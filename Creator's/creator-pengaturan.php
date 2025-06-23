<?php
include 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch user (creator) details
$stmt = $pdo->prepare("SELECT username, fullname, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();

$creator_name = $user_data['username'] ?? '';
$creator_fullname = $user_data['fullname'] ?? '';
$creator_email = $user_data['email'] ?? '';

// "Deskripsi Profil" is not in the `users` table in onverse.sql.
// Assuming it might be a new column 'description' or a placeholder.
$creator_description = "Penulis novel romansa yang akan menggetarkan hatimu."; // Static placeholder
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengaturan Akun - OnVerse</title>
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
            <h1>Pengaturan Akun</h1>
          </div>
        </header>

        <div class="content-panel">
          <form class="settings-form" method="POST" action="creator-pengaturan.php">
            <div class="form-group">
              <label for="creator-name">Nama Kreator</label>
              <input type="text" id="creator-name" name="creator_name" value="<?php echo htmlspecialchars($creator_name); ?>" />
            </div>
            <div class="form-group">
              <label for="creator-desc">Deskripsi Profil</label>
              <textarea id="creator-desc" name="creator_desc" rows="4"><?php echo htmlspecialchars($creator_description); ?></textarea>
            </div>
            <div class="form-group">
              <label for="creator-email">Email</label>
              <input
                type="email"
                id="creator-email"
                name="creator_email"
                value="<?php echo htmlspecialchars($creator_email); ?>"
              />
            </div>
            <div class="form-group">
              <label for="creator-password">Password Baru</label>
              <input
                type="password"
                id="creator-password"
                name="creator_password"
                placeholder="Kosongkan jika tidak ingin diubah"
              />
            </div>
            <button type="submit" class="btn btn-primary">
              Simpan Perubahan
            </button>
          </form>
        </div>
      </main>
    </div>
    <script src="script.js"></script>
  </body>
</html>
<?php
// Handle form submission (simplistic, no real update logic for description/password)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['creator_name'] ?? '';
    $new_email = $_POST['creator_email'] ?? '';
    $new_password = $_POST['creator_password'] ?? '';
    $new_description = $_POST['creator_desc'] ?? ''; // This would require a 'description' column

    $update_query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $params = [$new_username, $new_email, $user_id];

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
        $params = [$new_username, $new_email, $hashed_password, $user_id];
    }

    try {
        $stmt = $pdo->prepare($update_query);
        $stmt->execute($params);
        // If 'description' column existed in 'users':
        // $stmt_desc = $pdo->prepare("UPDATE users SET description = ? WHERE id = ?");
        // $stmt_desc->execute([$new_description, $user_id]);

        echo "<script>alert('Pengaturan berhasil diperbarui!'); window.location.href='creator-pengaturan.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Terjadi kesalahan saat memperbarui pengaturan: " . $e->getMessage() . "');</script>";
    }
}
?>