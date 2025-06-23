<?php
// php/upload_work.php
session_start(); // Pastikan sesi dimulai di awal
header('Content-Type: application/json');

include 'db.php'; // Sertakan file koneksi database

$response = ['success' => false, 'message' => ''];

// 1. Verifikasi Autentikasi Pengguna
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not authenticated. Please log in.';
    echo json_encode($response);
    exit();
}

$author_id = $_SESSION['user_id'];

// 2. Ambil Status Kreator dan Verifikasi dari Database (Penting: selalu ambil dari DB untuk data terbaru)
$stmt = $pdo->prepare("SELECT creator, verified, role FROM users WHERE id = ?"); // Ambil juga role
$stmt->execute([$author_id]);
$user_db_status = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user_db_status) {
    // User tidak ditemukan di DB meskipun ada sesi ID
    $response['message'] = 'User data not found in database. Please log in again.';
    session_destroy(); // Hancurkan sesi yang tidak valid
    echo json_encode($response);
    exit();
}

$is_creator_in_db = (bool)$user_db_status['creator'];
$is_verified_in_db = (bool)$user_db_status['verified'];
$user_role_in_db = $user_db_status['role']; // Ambil role dari DB

// BARU: Perbarui $_SESSION dengan status terbaru dari database
// Ini penting agar sesi di server selalu mencerminkan status DB terkini
$_SESSION['is_creator'] = $is_creator_in_db;
$_SESSION['verified'] = $is_verified_in_db;
$_SESSION['userRole'] = $user_role_in_db; // Perbarui juga role di sesi

// Ambil Data Form Lainnya
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['synopsis'] ?? '');
$genre1 = trim($_POST['kategori1'] ?? '');
$genre2 = trim($_POST['kategori2'] ?? null);

// 3. Validasi Data Form Awal (sebelum penanganan file & DB)
if (empty($title) || empty($description) || empty($genre1)) {
    $response['message'] = 'Work Title, Synopsis, and Category 1 are required.';
    echo json_encode($response);
    exit();
}

// Sanitasi judul karya untuk nama folder
$sanitized_title = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '_', $title));
if (empty($sanitized_title)) {
    $sanitized_title = 'untitled_work_' . uniqid(); // Fallback jika judul kosong/hanya karakter tidak valid
}

// Tentukan direktori upload spesifik untuk karya ini
$baseUploadRoot = '../database/karya/';
$workBaseDir = $baseUploadRoot . $sanitized_title . '/';
$thumbnailUploadDir = $workBaseDir . 'Thumbnail/'; // Direktori spesifik untuk Thumbnail

// Buat direktori jika belum ada (termasuk folder Thumbnail)
if (!is_dir($thumbnailUploadDir)) {
    if (!mkdir($thumbnailUploadDir, 0777, true)) {
        $response['message'] = 'Failed to create work or thumbnail directory. Check permissions: ' . $thumbnailUploadDir;
        echo json_encode($response);
        exit();
    }
}

// 4. Tangani Upload Thumbnail
$thumbnail_fileName = null;
$filePath = null; 
if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['thumbnail'];
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($fileExt, $allowed)) {
        $response['message'] = 'Invalid thumbnail file type. Only JPG, JPEG, PNG, GIF are allowed.';
        echo json_encode($response);
        exit();
    }
    if ($file['size'] > 5000000) { // Max 5MB
        $response['message'] = 'Thumbnail file is too large (max 5MB).';
        echo json_encode($response);
        exit();
    }

    $thumbnail_fileName = uniqid('thumb_', true) . '.' . $fileExt;
    $filePath = $thumbnailUploadDir . $thumbnail_fileName; 

    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        $response['message'] = 'Failed to upload thumbnail to work directory.';
        echo json_encode($response);
        exit();
    }
} else {
    $response['message'] = 'Thumbnail image is required.';
    echo json_encode($response);
    exit();
}

// Simpan Data ke Database (Tabel 'works')
try {
    // 5. Logic Pendaftaran Kreator Otomatis atau Penolakan
    $final_work_status = 'draft'; // Default untuk karya yang belum diverifikasi atau sedang diverifikasi
    $user_needs_update = false; // Flag untuk user yang perlu di-update statusnya di DB
    
    if (!$is_creator_in_db) {
        // KASUS 1: User bukan kreator di DB, otomatis daftar jadi kreator dan karya pending
        $user_needs_update = true;
        // set 'creator' = 1, 'verified' = 0, 'role' = 'author'
        $response['message'] = 'Your creator application and work submission are pending admin approval.';
        $response['status_code'] = 'creator_application_pending';
        $final_work_status = 'draft'; // Karya status draft sampai kreator diverifikasi
        
    } elseif ($is_creator_in_db && !$is_verified_in_db) {
        // KASUS 2: User sudah jadi kreator di DB tapi belum diverifikasi
        $response['message'] = 'Your creator account is pending verification. Work cannot be published until approved.';
        $response['status_code'] = 'pending_verification';
        
        // Hapus thumbnail karena karya tidak akan disimpan atau akan disimpan sebagai draft terpisah
        if ($thumbnail_fileName && file_exists($filePath)) {
            unlink($filePath);
            if (is_dir($thumbnailUploadDir) && count(scandir($thumbnailUploadDir)) == 2) {
                rmdir($thumbnailUploadDir);
            }
            if (is_dir($workBaseDir) && count(scandir($workBaseDir)) == 2) { 
                rmdir($workBaseDir);
            }
        }
        echo json_encode($response); // Kirim respons dan keluar
        exit();
        
    } else { // ($is_creator_in_db && $is_verified_in_db)
        // KASUS 3: User sudah jadi kreator dan sudah diverifikasi
        $final_work_status = 'published'; // Karya langsung publish
        $response['message'] = 'Work published successfully!';
    }

    // Lakukan UPDATE user jika diperlukan (hanya jika KASUS 1 terjadi)
    if ($user_needs_update) {
        $stmt_update_user = $pdo->prepare("UPDATE users SET creator = 1, verified = 0, role = 'author' WHERE id = ?");
        $stmt_update_user->execute([$author_id]);
        
        // Perbarui sesi lagi setelah update DB agar konsisten dengan `upload_work.php` ini
        $_SESSION['is_creator'] = true;
        $_SESSION['verified'] = false;
        $_SESSION['userRole'] = 'author';
    }
    
    // Simpan thumbnail_url relatif terhadap root website
    $full_thumbnail_url_for_db = 'database/karya/' . $sanitized_title . '/Thumbnail/' . $thumbnail_fileName; 

    $stmt_insert_work = $pdo->prepare(
        "INSERT INTO works (author_id, title, description, thumbnail_url, genre, genre2, status, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
    );

    $stmt_insert_work->execute([
        $author_id,
        $title,
        $description,
        $full_thumbnail_url_for_db,
        $genre1,
        $genre2,
        $final_work_status // Menggunakan status karya yang ditentukan
    ]);

    $response['success'] = true;
    // Pesan sukses sudah diatur di masing-masing kasus (published/pending_application)
    // Jika $response['message'] belum diset untuk kasus published, set di sini
    if (!isset($response['message']) && $final_work_status === 'published') {
         $response['message'] = 'Work published successfully!';
    }


} catch (PDOException $e) {
    // Tangani error database
    if ($thumbnail_fileName && file_exists($filePath)) {
        unlink($filePath);
    }
    if (is_dir($thumbnailUploadDir) && count(scandir($thumbnailUploadDir)) == 2) {
        rmdir($thumbnailUploadDir);
    }
    if (is_dir($workBaseDir) && count(scandir($workBaseDir)) == 2) {
        rmdir($workBaseDir);
    }
    $response['message'] = 'Database error: ' . $e->getMessage();
    error_log("Upload work error: " . $e->getMessage());
} catch (Exception $e) {
    // Tangani error umum lainnya
    if ($thumbnail_fileName && file_exists($filePath)) {
        unlink($filePath);
    }
    if (is_dir($thumbnailUploadDir) && count(scandir($thumbnailUploadDir)) == 2) {
        rmdir($thumbnailUploadDir);
    }
    if (is_dir($workBaseDir) && count(scandir($workBaseDir)) == 2) {
        rmdir($workBaseDir);
    }
    $response['message'] = 'Server error: ' . $e->getMessage();
    error_log("Upload work general error: " . $e->getMessage());
}

echo json_encode($response);
exit();
?>