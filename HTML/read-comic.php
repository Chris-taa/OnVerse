<?php
// episode-read.php
session_start(); // Start session to access session variables (e.g., login status)
include '../php/db.php'; // Include your database connection file

$episode_id = null;
$comic_title = "Comic";
$episode_number = "N/A";
$episode_title = "Episode";
$images = []; // Array to store image URLs

// Check if episode_id is provided in the URL
if (isset($_GET['episode_id'])) {
    $episode_id = filter_var($_GET['episode_id'], FILTER_SANITIZE_NUMBER_INT); // Sanitize the input

    try {
        // Fetch episode details (comic title, episode number, episode title)
        $stmt_episode_details = $pdo->prepare("SELECT e.episode_number, e.title AS episode_title, e.work_id,
                                                      w.title AS comic_title
                                               FROM episodes e
                                               JOIN works w ON e.work_id = w.work_id
                                               WHERE e.episode_id = ?");
        $stmt_episode_details->execute([$episode_id]);
        $episode_data = $stmt_episode_details->fetch(PDO::FETCH_ASSOC);

        if ($episode_data) {
            $comic_title = htmlspecialchars($episode_data['comic_title']);
            $episode_number = htmlspecialchars($episode_data['episode_number']);
            $episode_title = htmlspecialchars($episode_data['episode_title']);
            $work_id = htmlspecialchars($episode_data['work_id']); // Get work_id to link back to detail page

            // Fetch chapter images for the episode
            $stmt_images = $pdo->prepare("SELECT image_url FROM chapter_images WHERE episode_id = ? ORDER BY image_order ASC");
            $stmt_images->execute([$episode_id]);
            $images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);

            // Fetch previous and next episode for navigation
            // Get all episode numbers for this work, ordered
            $stmt_all_episodes = $pdo->prepare("SELECT episode_id, episode_number FROM episodes WHERE work_id = ? ORDER BY episode_number ASC");
            $stmt_all_episodes->execute([$work_id]);
            $all_episodes = $stmt_all_episodes->fetchAll(PDO::FETCH_ASSOC);

            $prev_episode_id = null;
            $next_episode_id = null;
            $current_index = -1;

            foreach ($all_episodes as $index => $ep) {
                if ($ep['episode_id'] == $episode_id) {
                    $current_index = $index;
                    break;
                }
            }

            if ($current_index > 0) {
                $prev_episode_id = $all_episodes[$current_index - 1]['episode_id'];
            }
            if ($current_index < count($all_episodes) - 1) {
                $next_episode_id = $all_episodes[$current_index + 1]['episode_id'];
            }

        } else {
            // Episode not found
            $comic_title = "Episode Not Found";
            $episode_title = "";
            $episode_number = "N/A";
            $images = [];
        }

    } catch (PDOException $e) {
        error_log("Database error fetching episode details: " . $e->getMessage());
        $comic_title = "Error";
        $episode_title = "An error occurred.";
        $episode_number = "N/A";
        $images = [];
    }
} else {
    // No episode_id provided in the URL
    $comic_title = "Invalid Request";
    $episode_title = "No episode ID provided.";
    $episode_number = "N/A";
    $images = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca Komik - <?php echo $comic_title . " Chapter " . $episode_number; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Variabel dari CSS Anda yang relevan */
        :root {
            --primary-blue: #2563EB;
            --dark-blue: #0e3894;
            --white: #ffffff;
            --dark-text: #333;
            --gray-text: #666;
            --border-color: #ddd;
            --bg-light: #f0f2f5;
            --header-dark-bg: #333;
        }

        /* Gaya Umum */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--dark-text);
            line-height: 1.6;
        }

        .reader-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header Mirip Navbar Landing */
        .reader-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            background-color: var(--header-dark-bg);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .reader-header .left-section {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-grow: 1;
            max-width: 300px; /* Sesuaikan sesuai kebutuhan */
        }

        .reader-header .back-button {
            text-decoration: none;
            color: var(--white);
            font-size: 1.2em;
            transition: color 0.3s ease;
        }

        .reader-header .back-button:hover {
            color: #eee;
        }

        .reader-header .comic-title-display {
            font-size: 1.1em;
            font-weight: 600;
            color: var(--white);
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Gaya Chapter Navigasi Baru */
        .reader-header .chapter-nav {
            font-size: 1.1em;
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            margin: 0 auto;
            flex-shrink: 0;
        }

        .reader-header .chapter-nav a {
            color: var(--white);
            text-decoration: none;
            font-weight: normal;
            transition: color 0.3s ease;
        }

        .reader-header .chapter-nav a:hover {
            color: #ccc;
        }

        .reader-header .right-section {
            display: flex;
            gap: 20px;
            flex-grow: 1;
            justify-content: flex-end;
            max-width: 300px;
        }

        .reader-header .icon-button {
            background: none;
            border: none;
            color: var(--white);
            cursor: pointer;
            font-size: 1.3em;
            padding: 5px;
            transition: color 0.3s ease;
        }

        .reader-header .icon-button:hover {
            color: #eee;
        }

        /* Viewer Komik */
        .comic-page-viewer {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            background-color: var(--bg-light);
        }

        .comic-page {
            max-width: 800px;
            width: 90%;
            height: auto;
            display: block;
            margin-bottom: 8px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color: var(--white);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .reader-header {
                padding: 10px 20px;
            }
            .reader-header .left-section,
            .reader-header .right-section {
                flex-grow: 0;
                max-width: none;
            }
            .reader-header .left-section {
                gap: 10px;
            }
            .reader-header .comic-title-display {
                font-size: 1em;
            }
            .reader-header .chapter-nav {
                font-size: 1em;
                gap: 5px;
            }
            .reader-header .right-section {
                gap: 15px;
            }
            .reader-header .icon-button {
                font-size: 1.1em;
            }
            .comic-page {
                width: 95%;
            }
        }

        @media (max-width: 480px) {
            .reader-header {
                padding: 10px 15px;
            }
            .reader-header .left-section {
                gap: 8px;
            }
            .reader-header .comic-title-display {
                font-size: 0.9em;
            }
            .reader-header .chapter-nav {
                font-size: 0.9em;
            }
            .reader-header .right-section {
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="reader-container">
        <header class="reader-header">
            <div class="left-section">
                <a href="detail-page.php?work_id=<?php echo htmlspecialchars($work_id ?? ''); ?>" class="back-button">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <span class="comic-title-display"><?php echo $comic_title; ?></span>
            </div>
            <div class="chapter-nav">
                <?php if ($prev_episode_id !== null): ?>
                    <a href="episode-read.php?episode_id=<?php echo $prev_episode_id; ?>" aria-label="Previous Chapter">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php else: ?>
                    <span class="disabled-nav"><i class="fa-solid fa-chevron-left"></i></span>
                <?php endif; ?>

                <span class="current-chapter">Chapter <?php echo $episode_number; ?> - <?php echo $episode_title; ?></span>
                
                <?php if ($next_episode_id !== null): ?>
                    <a href="episode-read.php?episode_id=<?php echo $next_episode_id; ?>" aria-label="Next Chapter">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <span class="disabled-nav"><i class="fa-solid fa-chevron-right"></i></span>
                <?php endif; ?>
            </div>
            <div class="right-section">
                <button class="icon-button"><i class="fa-regular fa-bookmark"></i></button>
                <button class="icon-button"><i class="fa-regular fa-comment"></i></button>
            </div>
        </header>
        <main class="comic-page-viewer">
            <?php if (!empty($images)): ?>
                <?php foreach ($images as $image):
                    // Adjust image URL path. Assuming 'database/karya/...' is relative to project root.
                    $image_url_full = '../' . htmlspecialchars($image['image_url']);
                ?>
                    <img src="<?php echo $image_url_full; ?>" alt="Halaman Komik" class="comic-page">
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; margin-top: 50px;">No comic pages found for this episode.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>