<?php
// Pastikan session dimulai untuk otentikasi pengguna
session_start(); 

// Atur header tipe konten ke application/json
// Ini memberi tahu klien (JavaScript) bahwa respons akan dalam format JSON
header('Content-Type: application/json');

// Sertakan jalur yang benar untuk db.php
// Asumsikan skrip ini (upload_chapter.php) berada di folder 'php', dan db.php juga berada di 'php'
// Jika db.php berada di OnVerse/php/db.php dan upload_chapter.php juga di OnVerse/php/, maka 'db.php' sudah benar.
include 'db.php'; 

// Inisialisasi array respons dengan default false (gagal) dan pesan kosong
$response = ['success' => false, 'message' => ''];

// Fungsi untuk menghapus direktori dan isinya secara rekursif
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir.DIRECTORY_SEPARATOR.$object))
                    rrmdir($dir. DIRECTORY_SEPARATOR .$object);
                else
                    unlink($dir. DIRECTORY_SEPARATOR .$object);
            }
        }
        rmdir($dir);
    }
}

// 1. Otentikasi Pengguna dan Pemeriksaan Status Pembuat
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Pengguna tidak terotentikasi. Silakan masuk.';
    echo json_encode($response);
    exit();
}

$author_id = $_SESSION['user_id'];

// Penting: Verifikasi ulang status pembuat dan verifikasi dari database
// Ini mencegah masalah jika data sesi kedaluwarsa atau dimanipulasi
try {
    $stmt = $pdo->prepare("SELECT creator, verified FROM users WHERE id = ?");
    $stmt->execute([$author_id]);
    $user_status = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_status || !$user_status['creator'] || !$user_status['verified']) {
        $response['message'] = 'Anda harus menjadi pembuat terverifikasi untuk mengunggah bab. Akun Anda mungkin menunggu persetujuan.';
        echo json_encode($response);
        exit();
    }
} catch (PDOException $e) {
    $response['message'] = 'Kesalahan database saat verifikasi pengguna: ' . $e->getMessage();
    // Catat kesalahan secara internal untuk debugging
    error_log("Kesalahan verifikasi pengguna unggah bab: " . $e->getMessage()); 
    echo json_encode($response);
    exit();
}

// 2. Ambil Data Formulir dari POST
$work_id = $_POST['work_id'] ?? null;
$chapter_title = trim($_POST['chapter_title'] ?? '');
$comments_status = $_POST['comments_status'] ?? 'on'; // Default 'on'
$publish_type = $_POST['publish_type'] ?? 'schedule'; // Default 'schedule'
$release_date = null;

// Validasi input penting awal
if (empty($work_id) || empty($chapter_title)) {
    $response['message'] = 'ID Karya dan Judul Bab wajib diisi.';
    echo json_encode($response);
    exit();
}

// 3. Tentukan Nomor Episode dan Periksa Kepemilikan Karya
try {
    // Dapatkan nomor episode berikutnya untuk karya ini
    $stmt_episode_count = $pdo->prepare("SELECT MAX(episode_number) AS max_episode FROM episodes WHERE work_id = ?");
    $stmt_episode_count->execute([$work_id]);
    $max_episode = $stmt_episode_count->fetchColumn();
    $episode_number = ($max_episode !== null) ? $max_episode + 1 : 1;

    // Ambil detail karya (judul untuk nama folder) dan verifikasi kepemilikan
    $stmt_work = $pdo->prepare("SELECT title, author_id FROM works WHERE work_id = ?");
    $stmt_work->execute([$work_id]);
    $work_details = $stmt_work->fetch(PDO::FETCH_ASSOC);

    if (!$work_details) {
        $response['message'] = 'Karya tidak ditemukan.';
        echo json_encode($response);
        exit();
    }

    // Pastikan pengguna yang login adalah pemilik karya
    if ($work_details['author_id'] != $author_id) {
        $response['message'] = 'Anda tidak memiliki izin untuk mengunggah bab untuk karya ini.';
        echo json_encode($response);
        exit();
    }

    $work_title_for_folder = $work_details['title'];

} catch (PDOException $e) {
    $response['message'] = 'Kesalahan database saat mengambil detail karya: ' . $e->getMessage();
    error_log("Kesalahan detail karya unggah bab: " . $e->getMessage());
    echo json_encode($response);
    exit();
}

// 4. Tangani Tanggal Rilis berdasarkan Jenis Publikasi
date_default_timezone_set('Asia/Makassar'); // Zona waktu WITA (Denpasar, Bali, Indonesia)

if ($publish_type === 'schedule') {
    $schedule_date = $_POST['schedule_date'] ?? null;
    $schedule_time = $_POST['schedule_time'] ?? null;
    
    if (empty($schedule_date) || empty($schedule_time)) {
        $response['message'] = 'Tanggal dan waktu terjadwal wajib diisi untuk publikasi terjadwal.';
        echo json_encode($response);
        exit();
    }

    // Gabungkan tanggal dan waktu, pastikan detik selalu ":00" untuk strtotime
    $release_date_str = "$schedule_date $schedule_time:00"; 

    // Validasi string gabungan
    $timestamp = strtotime($release_date_str);
    if ($timestamp === false) { // Periksa secara ketat untuk false
        $response['message'] = 'Format tanggal atau waktu terjadwal tidak valid. Pastikan tanggal dan waktu valid.';
        error_log("Gagal strtotime untuk: " . $release_date_str . " dari tanggal: " . $schedule_date . ", waktu: " . $schedule_time);
        echo json_encode($response);
        exit();
    }
    $release_date = date('Y-m-d H:i:s', $timestamp); // Format untuk database

    // Pastikan tanggal terjadwal tidak di masa lalu
    if ($timestamp < time()) { // Periksa terhadap waktu saat ini
        $response['message'] = 'Tanggal rilis terjadwal tidak boleh di masa lalu.';
        echo json_encode($response);
        exit();
    }

} else { // publish_type === 'now'
    $release_date = date('Y-m-d H:i:s');
}


// 5. Bersihkan Judul Karya dan Buat Direktori Bab
// Pastikan nama folder bersih dan aman untuk sistem file
$sanitized_work_title = preg_replace('/[^a-zA-Z0-9_\-]/', '', str_replace(' ', '_', $work_title_for_folder));
if (empty($sanitized_work_title)) {
    $sanitized_work_title = 'untitled_work_' . $work_id; // Fallback jika judul kosong/tidak valid
}

$chapter_folder_name = 'Chapter_' . $episode_number;
// Jalur absolut dari root server web ke direktori upload
// __DIR__ adalah direktori tempat skrip PHP saat ini berada (e.g., OnVerse/php/)
// '../' naik satu level (ke OnVerse/)
// 'database/karya/' jalur ke folder target
$base_upload_dir = __DIR__ . '/../database/karya/' . $sanitized_work_title . '/' . $chapter_folder_name . '/';
// Jalur relatif yang akan disimpan di database (URL yang dapat diakses oleh browser)
$relative_db_path = 'database/karya/' . $sanitized_work_title . '/' . $chapter_folder_name . '/';


// Buat direktori jika belum ada
if (!is_dir($base_upload_dir)) {
    // Izin 0777 memberikan hak baca/tulis/eksekusi penuh, sesuaikan untuk produksi
    if (!mkdir($base_upload_dir, 0777, true)) { 
        $response['message'] = 'Gagal membuat direktori bab. Periksa izin direktori parent: ' . dirname($base_upload_dir);
        echo json_encode($response);
        exit();
    }
}


// 6. Tangani Unggahan Gambar Bab
$uploaded_file_data = []; // Simpan detail file beserta urutan yang diurutkan
// Periksa apakah ada file yang diunggah dan apakah $_FILES['chapter_images']['name'] adalah array (untuk multiple files)
if (isset($_FILES['chapter_images']) && is_array($_FILES['chapter_images']['name']) && !empty($_FILES['chapter_images']['name'][0])) {
    
    $files_to_process = [];
    foreach ($_FILES['chapter_images']['tmp_name'] as $key => $tmp_name) {
        // Hanya proses file yang berhasil diunggah (error code UPLOAD_ERR_OK)
        if ($_FILES['chapter_images']['error'][$key] == UPLOAD_ERR_OK) {
            $files_to_process[] = [
                'name' => $_FILES['chapter_images']['name'][$key],
                'type' => $_FILES['chapter_images']['type'][$key],
                'tmp_name' => $tmp_name,
                'size' => $_FILES['chapter_images']['size'][$key],
            ];
        } else {
            // Log kesalahan unggah individual (jangan langsung keluar di sini kecuali kesalahan fatal)
            $upload_error_message = 'Kesalahan unggah untuk ' . $_FILES['chapter_images']['name'][$key] . ': ';
            switch ($_FILES['chapter_images']['error'][$key]) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $upload_error_message .= 'File terlalu besar (melebihi batas PHP ini).';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $upload_error_message .= 'Unggahan tidak lengkap.';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $upload_error_message .= 'Tidak ada file yang diunggah.';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $upload_error_message .= 'Direktori sementara tidak ditemukan.';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $upload_error_message .= 'Gagal menulis file ke disk.';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $upload_error_message .= 'Ekstensi PHP menghentikan unggahan file.';
                    break;
                default:
                    $upload_error_message .= 'Kesalahan yang tidak diketahui.';
            }
            error_log($upload_error_message);
            // Anda bisa menambahkan pesan ke respons yang akan ditampilkan nanti,
            // tetapi untuk file tunggal yang gagal, biarkan loop berjalan.
            // Untuk multiple files, kita akan periksa $uploaded_file_data di akhir.
        }
    }

    // Urutkan file berdasarkan nama (urut alami) di sisi server sebagai cadangan/pemeriksaan ulang
    usort($files_to_process, function($a, $b) {
        return strnatcasecmp($a['name'], $b['name']);
    });

    foreach ($files_to_process as $file_data) {
        $file_name = $file_data['name'];
        $file_tmp = $file_data['tmp_name'];
        $file_size = $file_data['size'];
        $file_type = $file_data['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $allowed_mime_types = ['image/jpeg', 'image/png'];

        // Validasi tipe file dan ukuran
        if (!in_array($file_ext, $allowed_extensions) || !in_array($file_type, $allowed_mime_types)) {
            $response['message'] = 'Jenis file gambar bab tidak valid: ' . htmlspecialchars($file_name) . '. Hanya JPG, JPEG, PNG yang diizinkan.';
            // Bersihkan file yang berhasil diunggah sebelum keluar
            foreach ($uploaded_file_data as $data) {
                if (file_exists(__DIR__ . '/../' . $data['url'])) { 
                    unlink(__DIR__ . '/../' . $data['url']);
                }
            }
            // Hapus direktori yang dibuat jika kosong
            if (is_dir($base_upload_dir) && count(scandir($base_upload_dir)) == 2) {
                rmdir($base_upload_dir);
            }
            echo json_encode($response);
            exit();
        }

        if ($file_size > 10 * 1024 * 1024) { // Maks 10MB per gambar
            $response['message'] = 'Satu atau lebih gambar bab terlalu besar (maks 10MB): ' . htmlspecialchars($file_name);
            // Bersihkan
            foreach ($uploaded_file_data as $data) {
                if (file_exists(__DIR__ . '/../' . $data['url'])) {
                    unlink(__DIR__ . '/../' . $data['url']);
                }
            }
            if (is_dir($base_upload_dir) && count(scandir($base_upload_dir)) == 2) {
                rmdir($base_upload_dir);
            }
            echo json_encode($response);
            exit();
        }

        // Generate nama file unik
        $new_file_name = uniqid('chapter_img_', true) . '.' . $file_ext;
        // Jalur lengkap untuk menyimpan file di server
        $destination_path = $base_upload_dir . $new_file_name;

        // Pindahkan file yang diunggah
        if (move_uploaded_file($file_tmp, $destination_path)) {
            // Tambahkan detail file ke array $uploaded_file_data
            $uploaded_file_data[] = [
                'url' => $relative_db_path . $new_file_name, // Ini akan disimpan di DB
                'original_name' => $file_name // Untuk referensi
            ];
        } else {
            $response['message'] = 'Gagal memindahkan gambar bab yang diunggah ke server: ' . htmlspecialchars($file_name) . '. Pastikan izin tulis direktori: ' . $base_upload_dir;
            // Bersihkan file yang berhasil dipindahkan sebelumnya
            foreach ($uploaded_file_data as $data) {
                if (file_exists(__DIR__ . '/../' . $data['url'])) {
                    unlink(__DIR__ . '/../' . $data['url']);
                }
            }
            // Hapus direktori jika kosong
            if (is_dir($base_upload_dir) && count(scandir($base_upload_dir)) == 2) {
                rmdir($base_upload_dir);
            }
            echo json_encode($response);
            exit();
        }
    }
}

// Jika tidak ada file yang berhasil diunggah (setelah semua pemrosesan)
if (empty($uploaded_file_data)) { 
    $response['message'] = 'Setidaknya satu file gambar yang valid diperlukan untuk bab (PNG, JPG, atau JPEG).';
    // Coba hapus direktori yang dibuat jika tidak ada gambar yang berhasil diunggah
    if (is_dir($base_upload_dir) && count(scandir($base_upload_dir)) == 2) {
        rmdir($base_upload_dir);
    }
    echo json_encode($response);
    exit();
}


// 7. Masukkan Data Bab ke dalam tabel 'episodes'
try {
    $stmt_insert_episode = $pdo->prepare(
        "INSERT INTO episodes (work_id, episode_number, title, is_premium, release_date, comments_enabled, created_at)
         VALUES (?, ?, ?, ?, ?, ?, NOW())"
    );

    // is_premium diasumsikan 0 (tidak premium) karena tidak ada input untuk itu di form HTML Anda.
    $is_premium = 0; 

    $stmt_insert_episode->execute([
        $work_id,
        $episode_number,
        $chapter_title,
        $is_premium,
        $release_date,
        ($comments_status === 'on' ? 1 : 0) // Konversi 'on'/'off' ke 1/0
    ]);

    $episode_id = $pdo->lastInsertId(); // Dapatkan ID dari episode yang baru dimasukkan

    // 8. Masukkan URL Gambar ke dalam tabel 'chapter_images' dengan urutan yang benar
    $stmt_insert_image = $pdo->prepare(
        "INSERT INTO chapter_images (episode_id, image_url, image_order) VALUES (?, ?, ?)"
    );

    $image_order = 1; // Inisialisasi penghitung urutan
    foreach ($uploaded_file_data as $data) { 
        $stmt_insert_image->execute([$episode_id, $data['url'], $image_order]);
        $image_order++; // Tingkatkan urutan untuk gambar berikutnya
    }

    $response['success'] = true;
    $response['message'] = 'Bab berhasil diunggah!';
    $response['episode_id'] = $episode_id;
    $response['episode_number'] = $episode_number;

} catch (PDOException $e) {
    // Jika penyisipan database gagal, bersihkan file yang diunggah dan direktori yang dibuat
    foreach ($uploaded_file_data as $data) {
        if (file_exists(__DIR__ . '/../' . $data['url'])) {
            unlink(__DIR__ . '/../' . $data['url']);
        }
    }
    // Hapus direktori yang dibuat jika kosong atau gagal disisipkan ke DB
    if (is_dir($base_upload_dir)) {
        rrmdir($base_upload_dir); // Gunakan fungsi rekursif untuk menghapus direktori
    }
    $response['message'] = 'Kesalahan database saat menyisipkan gambar bab: ' . $e->getMessage();
    error_log("Kesalahan DB unggah bab: " . $e->getMessage());
} catch (Exception $e) {
    $response['message'] = 'Kesalahan server selama pemrosesan gambar bab: ' . $e->getMessage();
    error_log("Kesalahan umum gambar bab: " . $e->getMessage());
}

// Encode array respons ke dalam string JSON dan output
echo json_encode($response);
?>
