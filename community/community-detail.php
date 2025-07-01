<?php
session_start();
require_once '../php/db.php';

$community_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$community_id) {
    header("Location: community-home.php");
    exit;
}

$isLoggedIn = isset($_SESSION['user_id']);
$user_id = $_SESSION['user_id'] ?? null;
$is_member = false;

if ($isLoggedIn) {
    $member_check_stmt = $pdo->prepare("SELECT COUNT(*) FROM community_members WHERE community_id = ? AND user_id = ?");
    $member_check_stmt->execute([$community_id, $user_id]);
    if ($member_check_stmt->fetchColumn() > 0)
        $is_member = true;
}

if ($isLoggedIn && !$is_member && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_community'])) {
    $pdo->prepare("INSERT IGNORE INTO community_members (community_id, user_id) VALUES (?, ?)")->execute([$community_id, $user_id]);
    $pdo->prepare("UPDATE communities SET member_count = member_count + 1 WHERE community_id = ?")->execute([$community_id]);
    header("Location: community-detail.php?id=" . $community_id);
    exit;
}

$stmt = $pdo->prepare("SELECT c.*, u.username AS creator_name FROM communities c JOIN users u ON c.creator_id = u.id WHERE c.community_id = ?");
$stmt->execute([$community_id]);
$community = $stmt->fetch();

if (!$community) {
    die("Komunitas tidak ditemukan.");
}

$threads_stmt = $pdo->prepare("SELECT t.*, u.username, u.photo, (SELECT COUNT(*) FROM thread_replies WHERE thread_id = t.thread_id) as reply_count FROM community_threads t JOIN users u ON t.user_id = u.id WHERE t.community_id = ? ORDER BY t.created_at DESC");
$threads_stmt->execute([$community_id]);
$threads = $threads_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($community['name']); ?> - OnVerse</title>
    <link rel="stylesheet" href="../css/landing.css">
    <link rel="stylesheet" href="../css/community-styles.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="community-page">
    <div id="navbar-placeholder"></div>

    <main class="community-container">
        <header class="form-header">
            <a href="community-home.php" class="btn btn-secondary btn-icon">
                <i data-feather="arrow-left"></i>
            </a>
        </header>

        <header class="community-detail-header"
            style="background-image: url('../<?php echo htmlspecialchars($community['banner_url'] ?? 'assets/img/default_banner.png'); ?>');">
            <div class="header-overlay"></div>
            <div class="header-content">
                <div class="info">
                    <h1><?php echo htmlspecialchars($community['name']); ?></h1>
                    <p><?php echo number_format($community['member_count']); ?> Anggota • Dikelola oleh
                        <?php echo htmlspecialchars($community['creator_name']); ?>
                    </p>
                </div>
                <div class="action">
                    <?php if ($isLoggedIn): ?>
                        <?php if ($is_member): ?>
                            <button class="btn btn-secondary" disabled><i data-feather="check-circle"></i> Anda Anggota</button>
                        <?php else: ?>
                            <form method="POST"><button type="submit" name="join_community" class="btn btn-success"><i
                                        data-feather="user-plus"></i> Gabung Komunitas</button></form>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="../login.php" class="btn btn-success"><i data-feather="log-in"></i> Masuk untuk
                            Bergabung</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="content-panel">
            <div class="thread-actions">
                <h3><i data-feather="message-square"></i> Diskusi Terbaru</h3>
                <?php if ($is_member): ?>
                    <a href="create-thread.php?community_id=<?php echo $community_id; ?>" class="btn btn-primary"><i
                            data-feather="edit-3"></i> Buat Thread</a>
                <?php endif; ?>
            </div>
            <div class="thread-list">
                <?php if (empty($threads)): ?>
                    <p class="no-results">Belum ada diskusi di komunitas ini. Jadilah yang pertama!</p>
                <?php else: ?>
                    <?php foreach ($threads as $thread): ?>
                        <a href="community-thread.php?id=<?php echo $thread['thread_id']; ?>" class="thread-item">
                            <img src="../database/profiles/<?php echo htmlspecialchars($thread['photo'] ?? 'pp.jpg'); ?>"
                                alt="User" class="author-avatar-small">
                            <div class="thread-main">
                                <span class="thread-title"><?php echo htmlspecialchars($thread['title']); ?></span>
                                <span class="thread-meta">oleh
                                    <strong><?php echo htmlspecialchars($thread['username']); ?></strong> •
                                    <?php echo date('d M Y', strtotime($thread['created_at'])); ?></span>
                            </div>
                            <div class="thread-stats">
                                <span><i data-feather="message-circle"></i> <?php echo $thread['reply_count']; ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <div id="footer-placeholder"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
            const navbarPath = isLoggedIn ? "../components/navbar-loggedin.php" : "../components/navbar.html";

            fetch(navbarPath).then(r => r.text()).then(data => {
                document.getElementById("navbar-placeholder").innerHTML = data;
                feather.replace();
            });

            fetch("../components/footer.html").then(r => r.text()).then(data => {
                document.getElementById("footer-placeholder").innerHTML = data;
                feather.replace();
            });
        });
    </script>
</body>

</html>