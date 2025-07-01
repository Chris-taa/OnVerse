<?php
// landing.php (or index.php)
session_start(); // Start session if you need user data/login status
include '../php/db.php'; // Include your database connection file.
// Adjust path if landing.php is in web root and db.php is in web root/php/

// Initial fetch for "Daily" section - show comics for today by default
// Get current day of week (1=Monday, 2=Tuesday, ..., 7=Sunday in PHP date('w') but MySQL's DAYOFWEEK is 1=Sunday)
$currentDayPHP = date('w'); // 0 (for Sunday) through 6 (for Saturday)
$mysqlDayOfWeek = ($currentDayPHP == 0) ? 1 : ($currentDayPHP + 1); // Convert PHP's 0-6 to MySQL's 1-7
$initialDailyComics = [];

try {
  $stmt_daily_initial = $pdo->prepare("
        SELECT w.work_id, w.title, w.description, w.thumbnail_url, w.author_id, u.username
        FROM works w
        JOIN users u ON w.author_id = u.id
        WHERE w.status = 'published' AND DAYOFWEEK(w.created_at) = ?
        ORDER BY w.created_at DESC
        LIMIT 8
    ");
  $stmt_daily_initial->execute([$mysqlDayOfWeek]);
  $initialDailyComics = $stmt_daily_initial->fetchAll(PDO::FETCH_ASSOC);

  // Fetch author names and process data for initial daily comics
  foreach ($initialDailyComics as &$comic) {
    $comic['author_name'] = htmlspecialchars($comic['username']); // Use username from join
    $comic['thumbnail_url'] = htmlspecialchars($comic['thumbnail_url']);
    $comic['description'] = htmlspecialchars($comic['description']);
    if (strlen($comic['description']) > 100) {
      $comic['description'] = substr($comic['description'], 0, 97) . '...';
    }
    unset($comic['username']); // Clean up redundant data if not used
  }
  unset($comic); // Break the reference

} catch (PDOException $e) {
  error_log("Database error fetching initial daily comics: " . $e->getMessage());
  $initialDailyComics = []; // Fallback to empty array on error
}

// Fetch comics for "New Creation For You" section (e.g., status 'published', ordered by creation date)
try {
  $stmt_new = $pdo->prepare("
        SELECT w.work_id, w.title, w.description, w.thumbnail_url, w.author_id, u.username
        FROM works w
        JOIN users u ON w.author_id = u.id
        WHERE w.status = 'published'
        ORDER BY w.created_at DESC
        LIMIT 8
    "); // Limit to 8
  $stmt_new->execute();
  $new_comics = $stmt_new->fetchAll(PDO::FETCH_ASSOC);

  // Fetch author names for new comics
  foreach ($new_comics as &$comic) {
    $comic['author_name'] = htmlspecialchars($comic['username']); // Use username from join
    $comic['thumbnail_url'] = htmlspecialchars($comic['thumbnail_url']);
    $comic['description'] = htmlspecialchars($comic['description']);
    if (strlen($comic['description']) > 100) {
      $comic['description'] = substr($comic['description'], 0, 97) . '...';
    }
    unset($comic['username']); // Clean up redundant data if not used
  }
  unset($comic); // Break the reference

} catch (PDOException $e) {
  error_log("Database error fetching new comics: " . $e->getMessage());
  $new_comics = []; // Fallback to empty array on error
}

// Prepare the day name for initial active button
$currentDayName = date('l'); // Full textual representation of the day of the week (e.g., "Monday")

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>OnVerse</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
    rel="stylesheet" />

  <script src="https://unpkg.com/feather-icons"></script>

  <link rel="stylesheet" href="../css/landing.css" />
  <link rel="stylesheet" href="../css/milestone-popup.css" />
  <style>
    .days .day.active {
      background-color: var(--primary, #6200EE);
      /* Example primary color, adjust as per your theme */
      color: white;
      border-radius: 5px;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div id="navbar-placeholder"></div>

  <div id="sidebar-container"></div>
  <div id="sidebar-overlay" class="sidebar-overlay"></div>

  <section class="banner">
    <img src="../IMG/banner.png" alt="Banner" class="banner-image" />
    <div class="banner-carousel"></div>
  </section>

  <section class="content">
    <div class="days">
      <a href="javascript:void(0)" class="day" data-day-full="Sunday">SUN</a>
      <a href="javascript:void(0)" class="day" data-day-full="Monday">MON</a>
      <a href="javascript:void(0)" class="day" data-day-full="Tuesday">TUE</a>
      <a href="javascript:void(0)" class="day" data-day-full="Wednesday">WED</a>
      <a href="javascript:void(0)" class="day" data-day-full="Thursday">THU</a>
      <a href="javascript:void(0)" class="day" data-day-full="Friday">FRI</a>
      <a href="javascript:void(0)" class="day" data-day-full="Saturday">SAT</a>
    </div>
  </section>

  <section class="daily">
    <div class="comic-list" id="daily-comics-container">
      <?php if (!empty($initialDailyComics)): ?>
        <?php foreach ($initialDailyComics as $comic): ?>
          <a href="detail-page.php?work_id=<?php echo $comic['work_id']; ?>" class="comic-card-link">
            <div class="comic-card">
              <img src="../<?php echo $comic['thumbnail_url']; ?>" alt="<?php echo htmlspecialchars($comic['title']); ?>"
                class="comic-cover" />
              <h3 class="comic-title"><?php echo htmlspecialchars($comic['title']); ?></h3>
              <p class="comic-author"><?php echo $comic['author_name']; ?></p>
              <div class="comic-desc"><?php echo $comic['description']; ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align: center; grid-column: 1 / -1;">No daily comics found for today.</p>
      <?php endif; ?>
    </div>
  </section>

  <section class="karya-baru">
    <h1>New Creation For You</h1>
    <div class="comic-list">
      <?php if (!empty($new_comics)): ?>
        <?php foreach ($new_comics as $comic): ?>
          <a href="detail-page.php?work_id=<?php echo $comic['work_id']; ?>" class="comic-card-link">
            <div class="comic-card">
              <img src="../<?php echo $comic['thumbnail_url']; ?>" alt="<?php echo htmlspecialchars($comic['title']); ?>"
                class="comic-cover" />
              <h3 class="comic-title"><?php echo htmlspecialchars($comic['title']); ?></h3>
              <p class="comic-author"><?php echo $comic['author_name']; ?></p>
              <div class="comic-desc"><?php echo $comic['description']; ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align: center; grid-column: 1 / -1;">No new creations found.</p>
      <?php endif; ?>
    </div>
  </section>

  <section class="genre">
    <h1>Genre</h1>
    <div class="genre-buttons">
      <a href="genre-page.php?genre=Action" class="genre-btn">Action</a>
      <a href="genre-page.php?genre=Romance" class="genre-btn">Romance</a>
      <a href="genre-page.php?genre=Comedy" class="genre-btn">Comedy</a>
      <a href="genre-page.php?genre=Drama" class="genre-btn">Drama</a>
      <a href="genre-page.php?genre=Fantasy" class="genre-btn">Fantasy</a>
      <a href="genre-page.php?genre=Horror" class="genre-btn">Horror</a>
      <a href="genre-page.php?genre=Slice of Life" class="genre-btn">Slice of Life</a>
      <a href="genre-page.php?genre=Adventure" class="genre-btn">Adventure</a>
      <a href="genre-page.php?genre=Mystery" class="genre-btn">Mystery</a>
      <a href="genre-page.php?genre=Sci-Fi" class="genre-btn">Sci-Fi</a>
      <a href="genre-page.php?genre=Thriller" class="genre-btn">Thriller</a>
      <a href="genre-page.php?genre=Supernatural" class="genre-btn">Supernatural</a>
      <a href="genre-page.php?genre=Historical" class="genre-btn">Historical</a>
      <a href="genre-page.php?genre=Sports" class="genre-btn">Sports</a>
      <a href="genre-page.php?genre=Music" class="genre-btn">Music</a>
      <a href="genre-page.php?genre=Mecha" class="genre-btn">Mecha</a>
      <a href="genre-page.php?genre=Isekai" class="genre-btn">Isekai</a>
      <a href="genre-page.php?genre=Psychological" class="genre-btn">Psychological</a>
      <a href="genre-page.php?genre=School Life" class="genre-btn">School Life</a>
      <a href="genre-page.php?genre=Superhero" class="genre-btn">Superhero</a>
    </div>
  </section>

  <div class="language-menu" id="languageMenu">
    <div class="language-item">
      <img src="https://flagcdn.com/w40/jp.png" alt="Japanese" title="Japanese" />
    </div>
    <div class="language-item">
      <img src="https://flagcdn.com/w40/kr.png" alt="Korean" title="Korean" />
    </div>
    <div class="language-item">
      <img src="https://flagcdn.com/w40/cn.png" alt="Chinese" title="Chinese" />
    </div>
    <div class="language-item">
      <img src="https://flagcdn.com/w40/id.png" alt="Indonesian" title="Indonesian" />
    </div>
  </div>

  <a class="show-button" id="showButton" style="display: none">
    <i data-feather="chevrons-right"></i>
  </a>

  <div id="footer-placeholder"></div>
  <div id="milestonePopup" class="popup-overlay">
    <div class="popup-content">
      <h2>Daily Milestones</h2>

      <div class="milestone-grid">
        <div class="milestone-item">
          <img src="../IMG/Coin.png" alt="5 koin" />
          <div class="milestone-amount">5 Coins</div>
          <div class="milestone-time">30 minutes</div>
        </div>
        <div class="milestone-item">
          <img src="../IMG/Coin.png" alt="10 koin" />
          <div class="milestone-amount">10 Coins</div>
          <div class="milestone-time">60 minutes</div>
        </div>
        <div class="milestone-item">
          <img src="../IMG/Coin.png" alt="15 koin" />
          <div class="milestone-amount">15 Coins</div>
          <div class="milestone-time">90 minutes</div>
        </div>
        <div class="milestone-item">
          <img src="../IMG/Coin.png" alt="20 koin" />
          <div class="milestone-amount">20 Coin</div>
          <div class="milestone-time">120 minutes</div>
        </div>
        <div class="milestone-item">
          <img src="../IMG/Coin.png" alt="25 koin" />
          <div class="milestone-amount">25 Coin</div>
          <div class="milestone-time">150 minutes</div>
        </div>
        <div class="milestone-item">
          <img src="../IMG/Coin.png" alt="30 koin" />
          <div class="milestone-amount">30 Coin</div>
          <div class="milestone-time">180 minutes</div>
        </div>
      </div>

      <button class="claim-button">Claim</button>
      <span class="close-button">&times;</span>
    </div>
  </div>
  <script src="../components/sidebar.js"></script>
  <script src="../JS/milestone-popup.js"></script>
  <script src="../JS/script.js"></script>
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

    function initializeFeatherIcons() {
      if (typeof feather !== "undefined") {
        feather.replace();
      } else {
        console.warn("Feather icons library not loaded.");
      }
    }

    function renderSidebar() {
      const sidebarContainer = document.getElementById("sidebar-container");
      const isLoggedIn = sessionStorage.getItem("isLoggedIn") === "true";
      const userRole = sessionStorage.getItem("userRole");
      const userVerified = sessionStorage.getItem("userVerified") === "true";
      if (sidebarContainer) {
        sidebarContainer.innerHTML = "";
        const newSidebar = createSidebar(
          isLoggedIn,
          userRole,
          userVerified,
          closeNav
        );
        sidebarContainer.appendChild(newSidebar);
      }
    }

    async function renderNavbar() {
      const navbarPlaceholder = document.getElementById("navbar-placeholder");
      const isLoggedIn = sessionStorage.getItem("isLoggedIn") === "true";
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

      if (typeof initializeLandingPageFeatures === "function") {
        initializeLandingPageFeatures();
      }

      if (typeof initializeMilestonePopup === "function") {
        initializeMilestonePopup();
      }

      initializeFeatherIcons();

      // --- Start of Day Filtering Logic ---
      const dayButtons = document.querySelectorAll('.days .day');
      const dailyComicsContainer = document.getElementById('daily-comics-container');

      // Function to render comic cards from data
      function renderComics(comics) {
        dailyComicsContainer.innerHTML = ''; // Clear existing comics
        if (comics.length === 0) {
          dailyComicsContainer.innerHTML = '<p style="text-align: center; grid-column: 1 / -1;">No comics found for this day.</p>';
          return;
        }

        comics.forEach(comic => {
          const comicCardHtml = `
                    <a href="detail-page.php?work_id=${comic.work_id}" class="comic-card-link">
                        <div class="comic-card">
                            <img src="../${comic.thumbnail_url}" alt="${comic.title}" class="comic-cover" />
                            <h3 class="comic-title">${comic.title}</h3>
                            <p class="comic-author">${comic.author_name}</p>
                            <div class="comic-desc">${comic.description}</div>
                        </div>
                    </a>
                `;
          dailyComicsContainer.innerHTML += comicCardHtml;
        });
      }

      // Function to fetch and display comics for a given day
      async function fetchAndDisplayComics(day) {
        dailyComicsContainer.innerHTML = '<p style="text-align: center; grid-column: 1 / -1;">Loading comics...</p>'; // Show loading message

        try {
          const response = await fetch(`../php/fetch_comics_by_day.php?day=${day}`);
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          const data = await response.json();

          if (data.status === 'success') {
            renderComics(data.comics);
          } else {
            dailyComicsContainer.innerHTML = `<p style="text-align: center; grid-column: 1 / -1;">Error: ${data.message}</p>`;
            console.error('Error fetching comics:', data.message);
          }
        } catch (error) {
          dailyComicsContainer.innerHTML = '<p style="text-align: center; grid-column: 1 / -1;">Failed to load comics. Please try again later.</p>';
          console.error('Network or parsing error:', error);
        }
      }

      // Add click event listeners to day buttons
      dayButtons.forEach(button => {
        button.addEventListener('click', function (event) {
          event.preventDefault(); // Prevent default anchor behavior
          const selectedDay = this.dataset.dayFull; // Get the full day name from data-day-full

          // Remove 'active' class from all buttons
          dayButtons.forEach(btn => btn.classList.remove('active'));
          // Add 'active' class to the clicked button
          this.classList.add('active');

          fetchAndDisplayComics(selectedDay);
        });
      });

      // Set the initial active day button and load comics for today
      const today = new Date();
      const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
      const currentDayName = dayNames[today.getDay()]; // getDay() returns 0 for Sunday, 1 for Monday, etc.

      // Find and activate the button for today
      const currentDayButton = document.querySelector(`.day[data-day-full="${currentDayName}"]`);
      if (currentDayButton) {
        currentDayButton.classList.add('active');
        // No need to call fetchAndDisplayComics here as PHP already renders today's comics
        // on initial page load. The JS will take over only when a day button is clicked.
      }
      // --- End of Day Filtering Logic ---
    });
  </script>
</body>

</html>