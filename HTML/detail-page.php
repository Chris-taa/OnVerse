<?php
// detail-page.php
session_start(); // Start session to access session variables (e.g., login status)
include '../php/db.php'; // Include your database connection file

$comic_title = "Comic Detail"; // Default title
$comic_synopsis = "Synopsis not available.";
$comic_genres = "N/A"; // Changed to plural as there can be two
$comic_author_name = "N/A";
$comic_cover_url = "../IMG/default_cover.jpg"; // Default cover image
$comic_author_id = null; // To potentially link to author's profile

$episodes = []; // Array to store episodes (formerly chapters)

// Prepare session data for JavaScript (consistent with your original script)
$isLoggedIn = isset($_SESSION['user_id']);
// Assuming 'role' and 'verified' are stored in session when user logs in from 'users' table
$userRole = $_SESSION['role'] ?? null; // 'role' column in users table
$userVerified = isset($_SESSION['verified']) && $_SESSION['verified'] === 1; // 'verified' column in users table (1 for verified)


// Check if work_id is provided in the URL
if (isset($_GET['work_id'])) {
    $work_id = filter_var($_GET['work_id'], FILTER_SANITIZE_NUMBER_INT); // Sanitize the input

    try {
        // Fetch comic details
        // Note: 'genre' and 'genre2' are directly from the 'works' table.
        // If you had a separate 'genres' table and a linking table (e.g., 'work_genres'),
        // the query would be more complex with JOINs.
        $stmt_comic = $pdo->prepare("SELECT w.work_id, w.title, w.description, w.thumbnail_url, w.genre, w.genre2,
                                            u.username AS author_name, u.id AS author_id
                                     FROM works w
                                     JOIN users u ON w.author_id = u.id
                                     WHERE w.work_id = ? AND w.status = 'published'");
        $stmt_comic->execute([$work_id]);
        $comic_data = $stmt_comic->fetch(PDO::FETCH_ASSOC);

        if ($comic_data) {
            $comic_title = htmlspecialchars($comic_data['title']);
            $comic_synopsis = htmlspecialchars($comic_data['description']);
            
            // Combine genre and genre2 if they exist
            $genres_array = [];
            if (!empty($comic_data['genre'])) {
                $genres_array[] = htmlspecialchars($comic_data['genre']);
            }
            if (!empty($comic_data['genre2'])) {
                $genres_array[] = htmlspecialchars($comic_data['genre2']);
            }
            $comic_genres = implode(', ', $genres_array) ?: 'N/A'; // Join with comma, or 'N/A' if both are empty

            $comic_author_name = htmlspecialchars($comic_data['author_name']);
            $comic_author_id = htmlspecialchars($comic_data['author_id']); // Get author ID
            $comic_cover_url = htmlspecialchars($comic_data['thumbnail_url']);
            
            // Adjust thumbnail URL if it's relative to project root (e.g., database/covers/image.jpg)
            if (strpos($comic_cover_url, 'http') === 0 || strpos($comic_cover_url, '/') === 0) {
                // Already absolute or starts from root, use as is
            } else {
                // Assuming it's like 'database/covers/image.jpg' from project root
                $comic_cover_url = '../' . $comic_cover_url;
            }

            // Fetch episodes for the comic (using 'episodes' table and 'episode_number')
            $stmt_episodes = $pdo->prepare("SELECT episode_id, episode_number, title, is_premium, release_date FROM episodes WHERE work_id = ? ORDER BY episode_number DESC");
            $stmt_episodes->execute([$work_id]);
            $episodes = $stmt_episodes->fetchAll(PDO::FETCH_ASSOC);

        } else {
            // Comic not found or not published
            $comic_title = "Comic Not Found";
            $comic_synopsis = "The comic you are looking for does not exist or is not yet published.";
            $comic_cover_url = "../IMG/default_cover.jpg"; // Set default if not found
        }

    } catch (PDOException $e) {
        error_log("Database error fetching comic details: " . $e->getMessage());
        $comic_title = "Error";
        $comic_synopsis = "An error occurred while loading comic details. Please try again later.";
        $comic_cover_url = "../IMG/default_cover.jpg"; // Set default on error
    }
} else {
    // No work_id provided in the URL
    $comic_title = "Invalid Request";
    $comic_synopsis = "No comic ID provided. Please select a comic from the homepage.";
    $comic_cover_url = "../IMG/default_cover.jpg"; // Set default if no ID
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Komik - <?php echo $comic_title; ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
      rel="stylesheet"
    />

    <script src="https://unpkg.com/feather-icons"></script>

    <link rel="stylesheet" href="../css/landing.css" />
    <link rel="stylesheet" href="../css/detail-page.css" />
    <link rel="stylesheet" href="../css/milestone-popup.css" />
  </head>
  <body>
    <div id="navbar-placeholder"></div>

    <div id="sidebar-placeholder"></div>
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <main class="container">
      <div class="content-wrapper">
        <div class="main-content">
          <section class="synopsis-box">
            <h2>Synopsis</h2>
            <p><?php echo $comic_synopsis; ?></p>
          </section>

          <section class="chapter-list">
            <?php if (!empty($episodes)): ?>
                <?php foreach ($episodes as $episode):
                    // You might want to fetch views for each episode from a separate table
                    // or add a 'views' column to the 'episodes' table if you want to display it
                    // For now, I'm assuming 'views' might be missing from your provided schema for episodes.
                    // If you track views per episode, add a 'views' column to your episodes table.
                    $episode_views = rand(1000, 500000); // Placeholder for views, replace with actual data if available
                    $formatted_views = number_format($episode_views / 1000, 1) . 'k';
                ?>
                    <a href="read-comic.php?episode_id=<?php echo $episode['episode_id']; ?>" class="chapter-item">
                        <span class="chapter-title">Chapter <?php echo htmlspecialchars($episode['episode_number']); ?> - <?php echo htmlspecialchars($episode['title']); ?></span>
                        <span class="chapter-date"><?php echo date('d/m/Y', strtotime($episode['release_date'])); ?></span>
                        <div class="chapter-info">
                            <span class="views"><i data-feather="heart" class="icon-heart"></i> <?php echo $formatted_views; ?></span>
                            <?php if ($episode['is_premium']): ?>
                                <i data-feather="award" class="icon-premium"></i>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-chapters">No chapters (episodes) found for this comic.</p>
            <?php endif; ?>
          </section>
        </div>

        <aside class="sidebar-info">
          <div class="comic-cover-box">
            <img
              src="<?php echo $comic_cover_url; ?>"
              alt="<?php echo $comic_title; ?> Cover"
              class="comic-cover-image"
            />
          </div>
          <div class="info-box">
            <h2>Genre</h2>
            <p><?php echo $comic_genres; ?></p>
          </div>
          <div class="info-box">
            <h2>Author</h2>
            <p><?php echo $comic_author_name; ?></p>
          </div>
        </aside>
      </div>
    </main>

    <div id="footer-placeholder"></div>

    <script src="../JS/script.js"></script>
    <script src="../JS/milestone-popup.js"></script>
    <script src="../components/sidebar.js"></script>
    <script>
      // Global functions for sidebar, defined here for easy access by other components
      function openNav() {
        const mySidebar = document.getElementById("mySidebar");
        const sidebarOverlay = document.getElementById("sidebar-overlay");
        if (mySidebar && sidebarOverlay) {
          mySidebar.style.width = "350px";
          mySidebar.classList.add("active");
          sidebarOverlay.style.display = "block";
          document.body.classList.add("sidebar-open");
        }
      }

      function closeNav() {
        const mySidebar = document.getElementById("mySidebar");
        const sidebarOverlay = document.getElementById("sidebar-overlay");
        if (mySidebar && sidebarOverlay) {
          mySidebar.style.width = "0";
          mySidebar.classList.remove("active");
          sidebarOverlay.style.display = "none";
          document.body.classList.remove("sidebar-open");
        }
      }

      // Re-initialize Feather icons after dynamic content is added
      function initializeFeatherIcons() {
        if (typeof feather !== "undefined") {
          feather.replace();
        } else {
          console.warn("Feather icons library not loaded.");
        }
      }

      // Function to load and render sidebar based on login status
      function renderSidebar() {
        const sidebarPlaceholder = document.getElementById("sidebar-placeholder");
        // Get data from PHP variables that were set by the server
        const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
        const userRole = <?php echo json_encode($userRole); ?>;
        const userVerified = <?php echo json_encode($userVerified); ?>; 

        if (sidebarPlaceholder) {
          sidebarPlaceholder.innerHTML = ""; 
          const newSidebar = createSidebar(
            isLoggedIn,
            userRole,
            userVerified,
            closeNav
          );
          sidebarPlaceholder.appendChild(newSidebar);
        }
      }

      // Function to load and render the correct navbar
      async function renderNavbar() {
        const navbarPlaceholder = document.getElementById("navbar-placeholder");
        // Get isLoggedIn status from PHP
        const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
        let navbarPath = isLoggedIn
          ? "../components/navbar-loggedin.php" 
          : "../components/navbar.html"; 

        try {
          const response = await fetch(navbarPath);
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          const data = await response.text();
          if (navbarPlaceholder) {
            navbarPlaceholder.innerHTML = data;
          } 
          if (typeof initializeNavbarFeatures === "function") {
            initializeNavbarFeatures();
          } 
          initializeFeatherIcons();
        } catch (error) {
          console.error("Error loading navbar:", error);
        }
      }

      // Main initialization logic that runs once the DOM is ready
      document.addEventListener("DOMContentLoaded", async function () {
        const loadFooterPromise = fetch("../components/footer.html") 
          .then((response) => response.text())
          .then((data) => {
            const footerPlaceholder =
              document.getElementById("footer-placeholder");
            if (footerPlaceholder) {
              footerPlaceholder.innerHTML = data;
            } else {
              console.error("Footer placeholder not found!");
            }
          })
          .catch((error) => {
            console.error("Error loading footer:", error);
          });

        await Promise.all([
          renderNavbar(), 
          renderSidebar(), 
          loadFooterPromise, 
        ]); 

        // Removed initializeLandingPageFeatures as it's not relevant for a detail page
        // if (typeof initializeLandingPageFeatures === "function") {
        //   initializeLandingPageFeatures();
        // } 

        if (typeof initializeMilestonePopup === "function") {
          initializeMilestonePopup();
        } 

        initializeFeatherIcons();
      });
    </script>
  </body>
</html>