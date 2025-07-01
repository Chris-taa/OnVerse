<?php
session_start();
require_once '../php/db.php';

header('Content-Type: application/json');

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['reply_id']) || !isset($data['action'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit;
}

$reply_id = filter_var($data['reply_id'], FILTER_SANITIZE_NUMBER_INT);
$action = $data['action'];

try {
    if ($action === 'like') {
        // Coba masukkan data like, abaikan jika sudah ada (karena UNIQUE KEY)
        $stmt = $pdo->prepare("INSERT IGNORE INTO thread_reply_likes (reply_id, user_id) VALUES (?, ?)");
        $stmt->execute([$reply_id, $user_id]);
    } elseif ($action === 'unlike') {
        // Hapus data like
        $stmt = $pdo->prepare("DELETE FROM thread_reply_likes WHERE reply_id = ? AND user_id = ?");
        $stmt->execute([$reply_id, $user_id]);
    }

    // Ambil jumlah like terbaru
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM thread_reply_likes WHERE reply_id = ?");
    $count_stmt->execute([$reply_id]);
    $newLikeCount = $count_stmt->fetchColumn();

    // Cek apakah user saat ini masih menyukai balasan tersebut
    $liked_check_stmt = $pdo->prepare("SELECT COUNT(*) FROM thread_reply_likes WHERE reply_id = ? AND user_id = ?");
    $liked_check_stmt->execute([$reply_id, $user_id]);
    $userHasLiked = $liked_check_stmt->fetchColumn() > 0;

    echo json_encode([
        'success' => true,
        'newLikeCount' => $newLikeCount,
        'liked' => $userHasLiked
    ]);

} catch (PDOException $e) {
    error_log("Like/Unlike Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
