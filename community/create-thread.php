<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$community_id = filter_input(INPUT_GET, 'community_id', FILTER_SANITIZE_NUMBER_INT);
if (!$community_id) {
    header("Location: community-home.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['thread-title']);
    $content = trim($_POST['thread-content']);
    $user_id = $_SESSION['user_id'];

    if (!empty($title) && !empty($content)) {
        $pdo->prepare("INSERT INTO community_threads (community_id, user_id, title, content) VALUES (?, ?, ?, ?)")->execute([$community_id, $user_id, $title, $content]);
        $pdo->prepare("UPDATE communities SET thread_count = thread_count + 1 WHERE community_id = ?")->execute([$community_id]);
        $new_thread_id = $pdo->lastInsertId();
        header("Location: community-thread.php?id=" . $new_thread_id);
        exit;
    } else {
        $error_message = "Judul dan konten tidak boleh kosong.";
    }
}

$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Thread Baru - OnVerse</title>
    <link rel="stylesheet" href="../css/landing.css">
    <link rel="stylesheet" href="../css/community-styles.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="community-page">
    <div id="navbar-placeholder"></div>

    <main class="community-container" style="padding-top: 100px;">
        <div class="content-panel"> <!-- Div pembungkus dengan padding -->
            <header class="form-header">
                <h1>Buat Diskusi Baru</h1>
                <a href="community-detail.php?id=<?php echo $community_id; ?>" class="btn btn-secondary btn-icon">
                    <i data-feather="arrow-left"></i>
                </a>
            </header>

            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <!-- Form dengan class yang sudah diperbaiki -->
            <form method="POST" class="create-thread-form">
                <div class="form-group">
                    <label for="thread-title">Judul Thread</label>
                    <input type="text" id="thread-title" name="thread-title"
                        placeholder="Apa yang ingin Anda diskusikan?" required>
                </div>
                <div class="form-group">
                    <label for="thread-content">Konten</label>
                    <textarea id="thread-content" name="thread-content" placeholder="Tuliskan isi diskusimu di sini..."
                        required></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Publikasikan Thread</button>
                </div>
            </form>
        </div>
    </main>

    <div id="footer-placeholder"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
            const navbarPath = isLoggedIn ? "../components/navbar-loggedin.php" : "../components/navbar.html";

            fetch(navbarPath).then(r => r.text()).then(data => { document.getElementById("navbar-placeholder").innerHTML = data; feather.replace(); });
            fetch("../components/footer.html").then(r => r.text()).then(data => { document.getElementById("footer-placeholder").innerHTML = data; feather.replace(); });
        });
    </script>
</body>

</html>