<?php
// genre-page.php
session_start();
include '../php/db.php';

// Get the genre from URL parameter
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$genre = htmlspecialchars($genre);

// Validate the genre exists in your list
$validGenres = [
  'Action',
  'Romance',
  'Comedy',
  'Drama',
  'Fantasy',
  'Horror',
  'Slice of Life',
  'Adventure',
  'Mystery',
  'Sci-Fi',
  'Thriller',
  'Supernatural',
  'Historical',
  'Sports',
  'Music',
  'Mecha',
  'Isekai',
  'Psychological',
  'School Life',
  'Superhero'
];

if (!in_array($genre, $validGenres)) {
  header("Location: landing.php");
  exit();
}

// Check login status and user role for sidebar
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $isLoggedIn ? ($_SESSION['role'] ?? 'reader') : null;
$userVerified = $isLoggedIn ? ($_SESSION['verified'] ?? false) : false;
$userName = $isLoggedIn ? ($_SESSION['username'] ?? 'User') : null;

// Fetch comics for the selected genre
try {
  $stmt = $pdo->prepare("SELECT w.work_id, w.title, w.description, w.thumbnail_url, w.author_id, 
                          u.username as author_name 
                          FROM works w
                          JOIN users u ON w.author_id = u.id
                          WHERE w.status = 'published' 
                          AND (w.genre = ? OR w.genre2 = ?)
                          ORDER BY w.created_at DESC");
  $stmt->execute([$genre, $genre]);
  $comics = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Process descriptions
  foreach ($comics as &$comic) {
    $comic['description'] = htmlspecialchars($comic['description']);
    if (strlen($comic['description']) > 100) {
      $comic['description'] = substr($comic['description'], 0, 97) . '...';
    }
  }
  unset($comic);

} catch (PDOException $e) {
  error_log("Database error fetching genre comics: " . $e->getMessage());
  $comics = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>OnVerse - <?php echo $genre; ?> Genre</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
    rel="stylesheet" />

  <script src="https://unpkg.com/feather-icons"></script>

  <link rel="stylesheet" href="../css/landing.css" />
  <link rel="stylesheet" href="../css/genre-page.css" />
  <style>
    /* Fix untuk masalah header menutupi konten */
    .genre-chosen-section {
      padding-top: 100px;
      /* Memberi ruang untuk header fixed */
      padding-bottom: 50px;
    }

    /* Styling untuk back button */
    .back-button {
      display: inline-flex;
      align-items: center;
      margin-right: 20px;
      color: #2563eb;
      text-decoration: none;
      font-weight: 600;
      padding: 8px 15px;
      border-radius: 5px;
      transition: background-color 0.3s ease;
      background-color: #f0f0f0;
    }

    .back-button:hover {
      background-color: rgba(37, 99, 235, 0.1);
    }

    .back-button i {
      margin-right: 5px;
    }

    /* Reorganisasi header section */
    .genre-header-container {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
      position: sticky;
      top: 80px;
      /* Sesuaikan dengan tinggi navbar */
      z-index: 100;
    }

    .genre-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .genre-title-container {
      display: flex;
      align-items: center;
      flex-grow: 1;
    }

    .genre-title-chosen {
      margin: 0;
      color: #2563eb;
      font-size: 2rem;
      text-transform: capitalize;
    }

    .sort-dropdown {
      display: flex;
      align-items: center;
      gap: 10px;
      background-color: #f8f8f8;
      padding: 8px 15px;
      border-radius: 8px;
    }

    .sort-dropdown select {
      padding: 8px 12px;
      border-radius: 5px;
      border: 1px solid #ddd;
      background-color: white;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .genre-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .genre-title-chosen {
        font-size: 1.8rem;
      }

      .sort-dropdown {
        width: 100%;
        justify-content: space-between;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <div id="navbar-placeholder"></div>

  <!-- Sidebar -->
  <div id="sidebar-container"></div>
  <div id="sidebar-overlay" class="sidebar-overlay"></div>

  <section class="genre-chosen-section">
    <!-- Header Container yang Sticky -->
    <div class="genre-header-container">
      <div class="genre-header">
        <div class="genre-title-container">
          <a href="landing.php" class="back-button">
            <i data-feather="arrow-left"></i> Back
          </a>
          <h1 class="genre-title-chosen"><?php echo $genre; ?></h1>
        </div>

        <div class="sort-dropdown">
          <label for="sort-by">Sort:</label>
          <select id="sort-by" name="sort-by">
            <option value="newest">Newest</option>
            <option value="popular">Popular</option>
            <option value="alphabetical">A-Z</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Comic List -->
    <div class="comic-list genre-specific-list">
      <?php if (!empty($comics)): ?>
        <?php foreach ($comics as $comic): ?>
          <a href="detail-page.php?work_id=<?php echo $comic['work_id']; ?>" class="comic-card-link">
            <div class="comic-card">
              <img src="../<?php echo $comic['thumbnail_url']; ?>" alt="<?php echo htmlspecialchars($comic['title']); ?>"
                class="comic-cover" />
              <h3 class="comic-title"><?php echo htmlspecialchars($comic['title']); ?></h3>
              <p class="comic-author"><?php echo htmlspecialchars($comic['author_name']); ?></p>
              <div class="comic-desc"><?php echo $comic['description']; ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align: center; grid-column: 1 / -1; color: #666; font-size: 1.2rem; padding: 50px 0;">
          No comics found in the <?php echo $genre; ?> genre.
        </p>
      <?php endif; ?>
    </div>
  </section>

  <div id="footer-placeholder"></div>

  <script src="../components/sidebar.js"></script>
  <script>
    // Global function for opening/closing sidebar
    function openNav() {
      document.getElementById("mySidebar").style.width = "250px";
      document.getElementById("sidebar-overlay").style.display = "block";
    }

    function closeNav() {
      document.getElementById("mySidebar").style.width = "0";
      document.getElementById("sidebar-overlay").style.display = "none";
    }

    // Function to load and render navbar based on login status
    async function renderNavbar() {
      const navbarPlaceholder = document.getElementById("navbar-placeholder");
      const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
      const navbarPath = isLoggedIn ?
        "../components/navbar-loggedin.php" :
        "../components/navbar.html";

      try {
        const response = await fetch(navbarPath);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.text();
        if (navbarPlaceholder) navbarPlaceholder.innerHTML = data;

        // Initialize navbar features if the function exists
        if (typeof initializeNavbarFeatures === 'function') {
          initializeNavbarFeatures();
        }
      } catch (error) {
        console.error("Error loading navbar:", error);
      }
    }

    // Function to load and render sidebar
    function renderSidebar() {
      const sidebarContainer = document.getElementById("sidebar-container");
      const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
      const userRole = '<?php echo $userRole; ?>';
      const userVerified = <?php echo $userVerified ? 'true' : 'false'; ?>;

      if (sidebarContainer) {
        sidebarContainer.innerHTML = "";
        const newSidebar = createSidebar(isLoggedIn, userRole, userVerified, closeNav);
        sidebarContainer.appendChild(newSidebar);
      }
    }

    // Function to load footer
    async function renderFooter() {
      const footerPlaceholder = document.getElementById("footer-placeholder");
      try {
        const response = await fetch("../components/footer.html");
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.text();
        if (footerPlaceholder) footerPlaceholder.innerHTML = data;
      } catch (error) {
        console.error("Error loading footer:", error);
      }
    }

    // Main initialization when DOM is loaded
    document.addEventListener("DOMContentLoaded", async function () {
      // Render all components
      await renderNavbar();
      renderSidebar();
      await renderFooter();

      // Initialize feather icons
      if (typeof feather !== 'undefined') {
        feather.replace();
      } else {
        console.warn("Feather icons not loaded");
      }

      // Add event listener for sorting dropdown
      document.getElementById('sort-by')?.addEventListener('change', function () {
        const sortValue = this.value;
        // In a real implementation, you would either:
        // 1. Reload the page with sort parameter
        // window.location.href = `genre-page.php?genre=<?php echo $genre; ?>&sort=${sortValue}`;
        // OR
        // 2. Use AJAX to fetch sorted results
        console.log(`Sorting by ${sortValue}`); // Placeholder for actual sorting logic
      });

      // Add hamburger menu event listener
      document.getElementById('hambuger-menu')?.addEventListener('click', function (e) {
        e.preventDefault();
        openNav();
      });
    });
  </script>
</body>

</html>