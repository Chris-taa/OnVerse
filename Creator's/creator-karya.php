<?php
include 'db_connect.php';

$author_id = $_SESSION['user_id'];

// Fetch works by the logged-in author
$stmt = $pdo->prepare("SELECT work_id, title, genre, status, created_at FROM works WHERE author_id = ? ORDER BY created_at DESC");
$stmt->execute([$author_id]);
$works = $stmt->fetchAll();

// For each work, get the episode count
foreach ($works as &$work) {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS episode_count FROM episodes WHERE work_id = ?");
    $stmt->execute([$work['work_id']]);
    $work['episode_count'] = $stmt->fetch()['episode_count'] ?? 0;

    // Determine the status tag class
    $status_class = '';
    switch ($work['status']) {
        case 'published':
            // Check if any episode is premium to decide between 'premium' and 'free' status tag
            $stmt_premium = $pdo->prepare("SELECT COUNT(*) AS premium_episodes FROM episodes WHERE work_id = ? AND is_premium = 1");
            $stmt_premium->execute([$work['work_id']]);
            $premium_episodes = $stmt_premium->fetch()['premium_episodes'] ?? 0;
            $status_class = ($premium_episodes > 0) ? 'premium' : 'free';
            break;
        case 'draft':
            $status_class = 'draft';
            break;
        case 'hidden':
            $status_class = 'hidden'; // Assuming 'hidden' status might also have a style
            break;
        default:
            $status_class = '';
    }
    $work['status_class'] = $status_class;
}
unset($work); // Break the reference with the last element
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Karya - OnVerse</title>
    <link rel="stylesheet" href="creator-style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />
    <style>
      /* Modal Styles */
      .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
      }

      .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 20px;
        border-radius: 8px;
        width: 500px;
        max-width: 90%;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
      }

      .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
      }

      .modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
      }

      .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
      }

      .modal-body {
        margin-bottom: 20px;
      }

      .form-group {
        margin-bottom: 15px;
      }

      .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
      }

      .form-group input,
      .form-group select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
      }

      .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
      }

      .btn {
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
      }

      .btn-secondary {
        background-color: #f0f0f0;
        border: 1px solid #ddd;
      }

      .btn-primary {
        background-color: #0038b4;
        color: white;
        border: none;
      }
    </style>
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
          <a href="creator-karya.php" class="nav-item active"
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
            <h1>Manajemen Karya</h1>
          </div>
          <div class="header-right">
            <a href="../HTML/create-karya.html" class="btn btn-primary"
              ><i class="fa-solid fa-plus"></i> Tambah Karya Baru</a
            >
          </div>
        </header>

        <div class="content-panel">
          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Judul Karya</th>
                  <th>Genre</th>
                  <th>Episode</th>
                  <th>Status</th>
                  <th>Update Terakhir</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($works)): ?>
                <tr>
                  <td colspan="6" style="text-align: center;">Belum ada karya yang dibuat.</td>
                </tr>
                <?php else: ?>
                  <?php foreach ($works as $work): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($work['title']); ?></td>
                    <td><?php echo htmlspecialchars($work['genre']); ?></td>
                    <td><?php echo $work['episode_count']; ?></td>
                    <td><span class="status-tag <?php echo htmlspecialchars($work['status_class']); ?>"><?php echo ucfirst($work['status']); ?></span></td>
                    <td><?php echo (new DateTime($work['created_at']))->format('d F Y'); ?></td>
                    <td class="action-buttons">
                    <a href="../HTML/upkarya.php?work_id=<?php echo htmlspecialchars($work['work_id']); ?>" class="create-chapter-btn">
                      <button class="btn-icon btn-blue" type="button"><i class="fa-solid fa-upload"></i></button>
                    </a>
                      <button class="btn-icon btn-green edit-work-btn"title="Edit Karya" 
                        data-work-id="<?php echo htmlspecialchars($work['work_id']); ?>" 
                        data-work-title="<?php echo htmlspecialchars($work['title']); ?>" 
                        data-work-status="<?php echo htmlspecialchars($work['status']); ?>">
                        <i class="fa-solid fa-pencil"></i>
                      </button>
                      <button class="btn-icon btn-red" title="Hapus Karya">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </td>
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



    <!-- Edit Work Modal -->
<div id="editWorkModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Edit Karya</h2>
      <button class="close-modal">&times;</button>
    </div>
    <div class="modal-body">
      <form id="editWorkForm">
        <input type="hidden" id="edit_work_id" name="work_id">
        <div class="form-group">
          <label for="edit_work_title">Judul Karya</label>
          <input type="text" id="edit_work_title" name="title" required>
        </div>
        <div class="form-group">
          <label for="edit_work_status">Status</label>
          <select id="edit_work_status" name="status" required>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
            <option value="hidden">Hidden</option>
          </select>
        </div>
        <div class="form-actions">
          <button type="button" class="btn btn-secondary close-modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
    <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Edit Work Modal Functionality
    const editButtons = document.querySelectorAll('.edit-work-btn');
    const modal = document.getElementById('editWorkModal');
    const closeButtons = document.querySelectorAll('.close-modal');
    
    // Open modal when edit button is clicked
    editButtons.forEach(button => {
      button.addEventListener('click', function() {
        const workId = this.getAttribute('data-work-id');
        const workTitle = this.getAttribute('data-work-title');
        const workStatus = this.getAttribute('data-work-status');
        
        document.getElementById('edit_work_id').value = workId;
        document.getElementById('edit_work_title').value = workTitle;
        document.getElementById('edit_work_status').value = workStatus;
        
        modal.style.display = 'block';
      });
    });
    
    // Close modal when X or cancel button is clicked
    closeButtons.forEach(button => {
      button.addEventListener('click', function() {
        modal.style.display = 'none';
      });
    });
    
    // Close modal when clicking outside the modal
    window.addEventListener('click', function(event) {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
    
    // Handle form submission
    document.getElementById('editWorkForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      
      fetch('update_work.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Karya berhasil diperbarui!');
          modal.style.display = 'none';
          location.reload(); // Refresh the page to show changes
        } else {
          alert('Gagal memperbarui karya: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Karya berhasil diperbarui!');
      });
    });
  });
  </script>
  </body>
</html>