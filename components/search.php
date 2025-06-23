<?php
// search.php

session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Include your database connection file (db.php)
// Make sure the path is correct. Assuming search.php is in 'components/'
// and db.php is in 'php/', then '../php/db.php' is correct if 'components'
// and 'php' are siblings under 'OnVerse'.
require_once '../php/db.php'; // This will make the $pdo object available

$search_query = '';
$search_results = [];

// Check if the search query 'q' is present in the URL
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search_query = htmlspecialchars($_GET['q']); // Sanitize input for display

    // --- Database Search Logic (NOW USING PDO) ---
    // IMPORTANT:
    // 1. Ganti 'works' dengan nama tabel yang sebenarnya jika berbeda
    // 2. Kolom yang dicari adalah 'title' dan 'description'
    // 3. Mengambil 'work_id' sebagai ID untuk link detail
    $sql = "SELECT work_id, title, description, thumbnail_url FROM works WHERE title LIKE ? OR description LIKE ?";

    // Prepare the statement using the $pdo object
    $stmt = $pdo->prepare($sql);

    // Add wildcards for partial matches
    $search_term = '%' . $search_query . '%';

    // Bind parameters and execute
    // For PDO, you pass an array to execute() for positional parameters
    $stmt->execute([$search_term, $search_term]);

    // Fetch all results
    $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // --- End Database Search Logic ---
}

// No need to close PDO connection explicitly, PHP will handle it at script end
// $pdo = null; // You can set it to null if you want to explicitly close

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results for "<?= htmlspecialchars($search_query) ?>"</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/landing.css">
    <link rel="stylesheet" href="../css/milestone-popup.css" />
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        /* Basic styling for search results page - Keep these as they are good */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4; /* Light gray background */
            min-height: 100vh; /* Ensure body takes full viewport height for consistent background */
            display: flex;
            flex-direction: column; /* To make sure footer (if any) stays at bottom */
        }
        .search-results-container {
            max-width: 960px;
            margin: 100px auto 20px auto; /* Add margin-top to prevent content from going under navbar */
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            flex-grow: 1; /* Allow container to grow and push footer down */
        }
        .search-results-container h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8em;
            text-align: center; /* Center the main heading */
        }
        .search-results-container > p { /* Target direct child <p> for "Found X results" */
            color: #555;
            margin-bottom: 15px;
            text-align: center;
        }
        .search-results-container ul {
            list-style: none;
            padding: 0;
            margin-top: 20px; /* Add some space above the list */
        }

        /* --- Perbaikan Utama untuk Item Daftar Hasil Pencarian --- */
        .search-results-container li {
            background-color: #ffffff; /* Warna latar belakang putih untuk setiap item */
            border: 1px solid #e0e0e0; /* Border lebih terang */
            border-radius: 8px; /* Sudut lebih membulat */
            margin-bottom: 15px;
            padding: 15px;
            display: flex; /* Menggunakan Flexbox untuk tata letak horizontal */
            align-items: flex-start; /* Mengatur item agar sejajar di bagian atas */
            box-shadow: 0 1px 3px rgba(0,0,0,0.08); /* Sedikit bayangan untuk kedalaman */
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; /* Efek hover halus */
        }

        .search-results-container li:hover {
            transform: translateY(-3px); /* Sedikit naik saat dihover */
            box-shadow: 0 4px 8px rgba(0,0,0,0.12); /* Bayangan lebih kuat saat dihover */
        }

        .search-results-container li .thumbnail {
            width: 100px; /* Ukuran thumbnail lebih besar */
            height: 120px; /* Tinggi disesuaikan dengan proporsi umum komik */
            object-fit: cover; /* Pastikan gambar mengisi kotak tanpa distorsi */
            margin-right: 20px; /* Jarak lebih jauh dari teks */
            border-radius: 4px; /* Sudut sedikit membulat */
            flex-shrink: 0; /* Mencegah thumbnail menyusut */
        }

        .search-results-container li .content {
            flex-grow: 1; /* Memungkinkan konten teks mengambil ruang yang tersisa */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Memastikan konten teks di tengah vertikal jika thumbnail lebih tinggi */
        }

        .search-results-container li h3 {
            margin-top: 0;
            margin-bottom: 8px; /* Jarak antara judul dan deskripsi */
            color: #333; /* Warna judul lebih gelap untuk kontras */
            font-size: 1.4em; /* Ukuran font judul lebih besar */
            line-height: 1.3;
        }

        .search-results-container li p {
            color: #666;
            line-height: 1.5;
            font-size: 0.9em; /* Ukuran font deskripsi sedikit lebih kecil */
            margin-bottom: 12px; /* Jarak antara deskripsi dan link */
            max-height: 60px; /* Batasi tinggi deskripsi jika terlalu panjang */
            overflow: hidden; /* Sembunyikan teks yang meluap */
            text-overflow: ellipsis; /* Tambahkan elipsis jika teks terpotong */
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Batasi deskripsi hingga 3 baris */
            -webkit-box-orient: vertical;
        }

        .search-results-container li .read-more { /* Kelas baru untuk link "Read More" */
            color: #007bff; /* Warna biru untuk link, konsisten dengan brand */
            text-decoration: none;
            font-weight: 600; /* Lebih tebal */
            align-self: flex-start; /* Posisikan link di awal baris */
            padding: 5px 10px; /* Tambahkan padding agar mudah diklik */
            border: 1px solid #007bff; /* Border untuk tombol */
            border-radius: 5px; /* Sudut membulat */
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        .search-results-container li .read-more:hover {
            background-color: #007bff;
            color: white;
            text-decoration: none;
        }

        /* Styling untuk pesan "No results found" */
        .search-results-container p.no-results-message {
            text-align: center;
            color: #777;
            font-style: italic;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <?php include 'navbar-loggedin.php'; ?>
    <div class="search-results-container">
        <h1>Search Results for "<?= htmlspecialchars($search_query) ?>"</h1>

        <?php if (!empty($search_results)): ?>
            <p>Found **<?= count($search_results) ?>** result(s):</p>
            <ul>
                <?php foreach ($search_results as $result): ?>
                    <li>
                        <?php if (!empty($result['thumbnail_url'])): ?>
                            <img src="../<?= htmlspecialchars($result['thumbnail_url']) ?>" alt="<?= htmlspecialchars($result['title'] ?? 'Thumbnail') ?>" class="thumbnail">
                        <?php endif; ?>
                        <div class="content">
                            <h3><?= htmlspecialchars($result['title'] ?? 'No Title') ?></h3>
                            <p><?= htmlspecialchars($result['description'] ?? 'No Description') ?></p>
                            <a href="../HTML/detail-page.php?work_id=<?= htmlspecialchars($result['work_id']) ?>" class="read-more">Read More</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (!empty($search_query)): ?>
            <p class="no-results-message">No results found for "<?= htmlspecialchars($search_query) ?>".</p>
            <p class="no-results-message">Try a different search term or check for typos.</p>
        <?php else: ?>
            <p class="no-results-message">Please enter a search query in the search bar above.</p>
        <?php endif; ?>
    </div>
    
    
    <script>
        // Initialize Feather Icons for all icons on the page
        feather.replace();
    </script>

        <!-- Add these right before </body> in search.php -->
    <script src="../components/sidebar.js"></script>
    <script src="../JS/script.js"></script>
    <script src="sidebar.js"></script>
    <script>
    // Initialize Feather Icons
    feather.replace();

    // Initialize navbar and sidebar
    document.addEventListener("DOMContentLoaded", function() {
        // Check if user is logged in (you might want to use PHP session here)
        const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        
        // Initialize navbar features
        if (typeof initializeNavbarFeatures === "function") {
        initializeNavbarFeatures();
        }
        
        // Initialize sidebar
        if (typeof initializeSidebar === "function") {
        initializeSidebar(isLoggedIn);
        }
    });
    </script>
</body>
</html>