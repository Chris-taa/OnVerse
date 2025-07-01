<?php
session_start();
require_once '../php/db.php';

$stmt = $pdo->query("
    SELECT 
        c.community_id, 
        c.name, 
        c.description,
        c.banner_url,
        u.username AS creator_name,
        (SELECT COUNT(*) FROM community_members WHERE community_id = c.community_id) AS member_count
    FROM communities c
    JOIN users u ON c.creator_id = u.id
    ORDER BY member_count DESC
");
$communities = $stmt->fetchAll(PDO::FETCH_ASSOC);

$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelajahi Komunitas - OnVerse</title>
    <link rel="stylesheet" href="../css/landing.css">
    <link rel="stylesheet" href="../css/community-styles.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
    <div id="navbar-placeholder"></div>

    <main class="community-container">
        <header class="form-header">
            <a href="../HTML/landing.php" class="btn btn-secondary btn-icon">
                <i data-feather="arrow-left"></i>
            </a>
        </header>
        <h1>Jelajahi Komunitas</h1>
        <p>Temukan ruang diskusi untuk karya favoritmu dan terhubung dengan kreatornya!</p>

        <div class="community-grid">
            <?php if (empty($communities)): ?>
                <p class="no-results" style="grid-column: 1 / -1;">Belum ada komunitas yang tersedia saat ini.</p>
            <?php else: ?>
                <?php foreach ($communities as $community): ?>
                    <a href="community-detail.php?id=<?php echo $community['community_id']; ?>" class="community-card-link">
                        <div class="community-card">
                            <div class="card-header"
                                style="background-image: url('../<?php echo htmlspecialchars($community['banner_url'] ?? 'assets/img/default_banner.png'); ?>');">
                            </div>
                            <div class="card-body">
                                <h3><?php echo htmlspecialchars($community['name']); ?></h3>
                                <p class="card-creator"><i
                                        data-feather="user"></i><strong><?php echo htmlspecialchars($community['creator_name']); ?></strong>
                                </p>
                                <p class="card-members"><i data-feather="users"></i>
                                    <?php echo number_format($community['member_count']); ?> Anggota</p>
                                <p class="card-description"><?php echo htmlspecialchars($community['description']); ?></p>
                                <span class="btn btn-primary">Kunjungi Komunitas</span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <div id="footer-placeholder"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
            const navbarPath = isLoggedIn ? "../components/navbar-loggedin.php" : "../components/navbar.html";

            fetch(navbarPath).then(res => res.ok ? res.text() : Promise.reject()).then(data => {
                document.getElementById("navbar-placeholder").innerHTML = data;
                feather.replace();
            }).catch(console.error);

            fetch("../components/footer.html").then(res => res.ok ? res.text() : Promise.reject()).then(data => {
                document.getElementById("footer-placeholder").innerHTML = data;
                feather.replace();
            }).catch(console.error);
        });
    </script>
</body>

</html>