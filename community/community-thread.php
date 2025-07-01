<?php
session_start();
require_once '../php/db.php'; // Pastikan path ini benar

$isLoggedIn = isset($_SESSION['user_id']);
$user_id = $_SESSION['user_id'] ?? null;

$thread_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$thread_id) {
    header("Location: community-home.php");
    exit;
}

// --- AMBIL DATA THREAD UTAMA ---
// Dipindahkan ke atas agar bisa mendapatkan community_id lebih awal
try {
    $stmt = $pdo->prepare("
        SELECT t.*, 
               u.username, 
               u.photo, 
               c.community_id,
               c.name AS community_name,
               (SELECT COUNT(*) FROM thread_replies WHERE thread_id = t.thread_id) AS reply_count
        FROM community_threads t 
        JOIN users u ON t.user_id = u.id 
        JOIN communities c ON t.community_id = c.community_id 
        WHERE t.thread_id = ?
    ");
    $stmt->execute([$thread_id]);
    $thread = $stmt->fetch();

    if (!$thread) {
        die("Thread tidak ditemukan.");
    }
} catch (PDOException $e) {
    die("Gagal mengambil data thread dari database: " . $e->getMessage());
}


// --- [BARU] CEK KEANGGOTAAN PENGGUNA DI KOMUNITAS ---
$is_member = false;
if ($isLoggedIn) {
    $community_id = $thread['community_id'];
    $member_check_stmt = $pdo->prepare("SELECT COUNT(*) FROM community_members WHERE community_id = ? AND user_id = ?");
    $member_check_stmt->execute([$community_id, $user_id]);
    if ($member_check_stmt->fetchColumn() > 0) {
        $is_member = true;
    }
}


// --- [MODIFIKASI] LOGIKA UNTUK MEMPROSES BALASAN BARU ---
// Ditambahkan pengecekan $is_member
if ($is_member && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_content'])) {
    $content = trim($_POST['reply_content']);
    $parent_reply_id = !empty($_POST['parent_reply_id']) ? filter_input(INPUT_POST, 'parent_reply_id', FILTER_SANITIZE_NUMBER_INT) : null;

    if (!empty($content)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO thread_replies (thread_id, user_id, content, parent_reply_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$thread_id, $user_id, $content, $parent_reply_id]);
            header("Location: community-thread.php?id=" . $thread_id);
            exit;
        } catch (PDOException $e) {
            // Sebaiknya catat error ini untuk proses debug
            error_log("Gagal menyimpan balasan: " . $e->getMessage());
        }
    }
}

// --- AMBIL SEMUA DATA BALASAN (REPLIES) ---
try {
    $replies_stmt = $pdo->prepare("
        SELECT r.*, u.username, u.photo, 
               (SELECT COUNT(*) FROM thread_reply_likes WHERE reply_id = r.reply_id) as like_count,
               (SELECT COUNT(*) FROM thread_reply_likes WHERE reply_id = r.reply_id AND user_id = ?) as user_liked
        FROM thread_replies r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.thread_id = ? 
        ORDER BY r.created_at ASC
    ");
    $replies_stmt->execute([$user_id, $thread_id]);
    $replies = $replies_stmt->fetchAll();

} catch (PDOException $e) {
    die("Gagal mengambil data balasan dari database: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($thread['title']); ?> - OnVerse</title>
    <link rel="stylesheet" href="../css/landing.css">
    <link rel="stylesheet" href="../css/community-styles.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
    <div id="navbar-placeholder"></div>

    <main class="community-container" style="padding-top: 100px;">
        <div class="content-panel">
            <header class="form-header">
                <a href="community-detail.php?id=<?php echo $thread['community_id']; ?>" class="btn btn-secondary"
                    style="padding: 8px;">
                    <i data-feather="arrow-left"></i> Kembali ke Komunitas
                </a>
            </header>

            <div class="thread-post-container">
                <div class="post-author-info">
                    <img src="../database/profiles/<?php echo htmlspecialchars($thread['photo'] ?? 'pp.jpg'); ?>"
                        alt="Avatar Penulis" class="author-avatar">
                    <div>
                        <span class="author-name"><?php echo htmlspecialchars($thread['username']); ?></span>
                        <span
                            class="post-timestamp"><?php echo date('d M Y, H:i', strtotime($thread['created_at'])); ?></span>
                    </div>
                </div>
                <div class="thread-post-body">
                    <h2><?php echo htmlspecialchars($thread['title']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($thread['content'])); ?></p>
                </div>
            </div>

            <div class="replies-container">
                <h3><?php echo count($replies); ?> Balasan</h3>

                <?php if (!empty($replies)): ?>
                    <?php foreach ($replies as $reply): ?>
                        <div class="comment-item" id="reply-<?php echo $reply['reply_id']; ?>">
                            <div class="post-author-info">
                                <img src="../database/profiles/<?php echo htmlspecialchars($reply['photo'] ?? 'pp.jpg'); ?>"
                                    alt="Avatar" class="author-avatar">
                                <div>
                                    <span class="author-name"><?php echo htmlspecialchars($reply['username']); ?></span>
                                    <span
                                        class="post-timestamp"><?php echo date('d M Y, H:i', strtotime($reply['created_at'])); ?></span>
                                </div>
                            </div>
                            <p class="comment-body"><?php echo nl2br(htmlspecialchars($reply['content'])); ?></p>
                            <div class="thread-post-actions">
                                <button class="btn-text btn-like <?php echo $reply['user_liked'] ? 'liked' : ''; ?>"
                                    data-reply-id="<?php echo $reply['reply_id']; ?>">
                                    <i data-feather="thumbs-up"></i>
                                    <span>Suka</span>
                                    (<span class="like-count"><?php echo $reply['like_count']; ?></span>)
                                </button>

                                <?php if ($is_member): // [BARU] Hanya tampilkan tombol balas jika user adalah anggota ?>
                                    <button class="btn-text btn-reply" data-reply-id="<?php echo $reply['reply_id']; ?>"
                                        data-username="<?php echo htmlspecialchars($reply['username']); ?>">
                                        <i data-feather="message-square"></i>
                                        <span>Balas</span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-results">Jadilah yang pertama membalas thread ini!</p>
                <?php endif; ?>

                <!-- [MODIFIKASI] FORMULIR BALASAN DENGAN LOGIKA BARU -->
                <div id="reply-form-wrapper">
                    <?php if ($is_member): ?>
                        <div class="reply-form-container" id="main-reply-form">
                            <h4 id="reply-form-title">Tulis Balasan Anda</h4>
                            <form method="POST">
                                <div class="form-group">
                                    <textarea name="reply_content" id="reply_content_textarea"
                                        placeholder="Tulis balasan Anda di sini..." required></textarea>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Kirim Balasan</button>
                                    <button type="button" id="cancel-reply-btn" class="btn btn-secondary"
                                        style="display: none;">Batal</button>
                                </div>
                            </form>
                        </div>
                    <?php elseif ($isLoggedIn): ?>
                        <div class="reply-form-container">
                            <p class="login-prompt" style="text-align: center; width: 100%; padding: 1rem 0;">
                                Anda harus menjadi anggota untuk dapat berdiskusi.
                                <a href="community-detail.php?id=<?php echo $thread['community_id']; ?>"
                                    class="btn btn-success" style="margin-top: 1rem;">
                                    <i data-feather="user-plus"></i> Gabung Komunitas
                                </a>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="reply-form-container">
                            <p class="login-prompt" style="text-align: center; width: 100%; padding: 1rem 0;">
                                <a href="../login.php">Masuk</a> atau <a href="../signup.php">Daftar</a> untuk ikut
                                berdiskusi.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <div id="footer-placeholder"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
            const isMember = <?php echo json_encode($is_member); ?>; // Variabel baru untuk JS
            const navbarPath = isLoggedIn ? "../components/navbar-loggedin.php" : "../components/navbar.html";

            fetch(navbarPath).then(res => res.ok ? res.text() : Promise.reject('Gagal memuat navbar')).then(data => {
                document.getElementById("navbar-placeholder").innerHTML = data;
                feather.replace();
            }).catch(console.error);

            fetch("../components/footer.html").then(res => res.ok ? res.text() : Promise.reject('Gagal memuat footer')).then(data => {
                document.getElementById("footer-placeholder").innerHTML = data;
                feather.replace();
            }).catch(console.error);

            // Logika Tombol Suka (Like)
            document.querySelectorAll('.btn-like').forEach(button => {
                button.addEventListener('click', function () {
                    if (!isLoggedIn) { window.location.href = '../login.php'; return; }
                    const replyId = this.dataset.replyId;
                    const likeCountSpan = this.querySelector('.like-count');
                    const isLiked = this.classList.contains('liked');
                    fetch('handle_like.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ reply_id: replyId, action: isLiked ? 'unlike' : 'like' })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                likeCountSpan.textContent = data.newLikeCount;
                                this.classList.toggle('liked', data.liked);
                            }
                        }).catch(console.error);
                });
            });

            // Hanya jalankan logika 'balas' jika pengguna adalah anggota
            if (isMember) {
                const replyFormWrapper = document.getElementById('reply-form-wrapper');
                const mainReplyForm = document.getElementById('main-reply-form');
                const parentIdInput = document.getElementById('parent_reply_id_input');
                const replyTextarea = document.getElementById('reply_content_textarea');
                const replyFormTitle = document.getElementById('reply-form-title');
                const cancelReplyBtn = document.getElementById('cancel-reply-btn');

                document.querySelectorAll('.btn-reply').forEach(button => {
                    button.addEventListener('click', function () {
                        const replyId = this.dataset.replyId;
                        const username = this.dataset.username;
                        const targetComment = document.getElementById('reply-' + replyId);

                        replyFormTitle.textContent = 'Balas ke @' + username;
                        replyTextarea.value = '@' + username + ' ';
                        parentIdInput.value = replyId;
                        cancelReplyBtn.style.display = 'inline-flex';

                        targetComment.appendChild(mainReplyForm);
                        replyTextarea.focus();
                    });
                });

                cancelReplyBtn.addEventListener('click', function () {
                    replyFormTitle.textContent = 'Tulis Balasan Anda';
                    replyTextarea.value = '';
                    parentIdInput.value = '';
                    cancelReplyBtn.style.display = 'none';
                    replyFormWrapper.appendChild(mainReplyForm);
                });
            }
        });
    </script>
</body>

</html>