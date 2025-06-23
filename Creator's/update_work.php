<?php
session_start();
header('Content-Type: application/json');

include 'db_connect.php';

$response = ['success' => false, 'message' => ''];

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Anda harus login terlebih dahulu.';
    echo json_encode($response);
    exit();
}

// Get form data
$work_id = $_POST['work_id'] ?? null;
$title = trim($_POST['title'] ?? '');
$status = $_POST['status'] ?? 'draft';

// Validate inputs
if (empty($work_id) || empty($title)) {
    $response['message'] = 'ID Karya dan Judul harus diisi.';
    echo json_encode($response);
    exit();
}

if (!in_array($status, ['published', 'draft', 'hidden'])) {
    $response['message'] = 'Status tidak valid.';
    echo json_encode($response);
    exit();
}

try {
    // Check if the work belongs to the current user
    $stmt = $pdo->prepare("SELECT work_id FROM works WHERE work_id = ? AND author_id = ?");
    $stmt->execute([$work_id, $_SESSION['user_id']]);
    $work = $stmt->fetch();
    
    if (!$work) {
        $response['message'] = 'Karya tidak ditemukan atau Anda tidak memiliki izin untuk mengedit.';
        echo json_encode($response);
        exit();
    }
    
    // Update the work
    $stmt = $pdo->prepare("UPDATE works SET title = ?, status = ? WHERE work_id = ?");
    $stmt->execute([$title, $status, $work_id]);
    
    $response['success'] = true;
    $response['message'] = 'Karya berhasil diperbarui.';
    
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    error_log("Update work error: " . $e->getMessage());
}

echo json_encode($response);
?>