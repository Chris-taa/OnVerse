<?php
// php/update_profile.php
session_start();
header('Content-Type: application/json');

include 'db.php'; // Koneksi ke database

$response = ['success' => false, 'message' => ''];

// Pastikan user_id ada di sesi
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not authenticated.';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];
// Mengambil tipe update dari header kustom yang dikirim JavaScript
$update_type = $_SERVER['HTTP_X_UPDATE_TYPE'] ?? null; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($update_type === 'photo') {
            // --- Logic untuk Update Photo ---
            if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
                // Periksa apakah error adalah UPLOAD_ERR_NO_FILE (tidak ada file diupload)
                if ($_FILES['photo']['error'] === UPLOAD_ERR_NO_FILE) {
                    throw new Exception('No photo selected for upload.');
                }
                throw new Exception('File upload error: ' . $_FILES['photo']['error']);
            }

            $file = $_FILES['photo'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];
            $fileType = $file['type'];

            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($fileExt, $allowed)) {
                throw new Exception('Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.');
            }
            if ($fileSize > 5000000) { // Max 5MB
                throw new Exception('File is too large (max 5MB).');
            }
            if ($fileError !== 0) {
                throw new Exception('There was an error uploading your file. Error code: ' . $fileError);
            }

            // Generate unique file name to prevent overwriting
            $newFileName = uniqid('', true) . "." . $fileExt;
            $uploadDir = '../IMG/'; // Direktori penyimpanan foto (sesuaikan path)
            // Pastikan direktori ada dan writable
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
                throw new Exception('Failed to create upload directory.');
            }
            $filePath = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmpName, $filePath)) {
                throw new Exception('Failed to move uploaded file. Check directory permissions.');
            }

            // Hapus foto lama jika bukan 'pp.jpg' default
            $stmt = $pdo->prepare("SELECT photo FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $oldPhoto = $stmt->fetchColumn();
            if ($oldPhoto && $oldPhoto !== 'pp.jpg' && file_exists($uploadDir . $oldPhoto)) {
                unlink($uploadDir . $oldPhoto);
            }

            // Update database dengan nama file foto baru
            $stmt = $pdo->prepare("UPDATE users SET photo = ? WHERE id = ?");
            $stmt->execute([$newFileName, $user_id]);

            $response['success'] = true;
            $response['message'] = 'Profile photo updated successfully!';
            $response['new_value'] = $newFileName; // Kirim nama file baru
            $_SESSION['user_photo'] = $newFileName; // Update sesi juga
        } else {
            // --- Logic untuk Update data teks (username, fullname, email, password) ---
            $data = json_decode(file_get_contents('php://input'), true);
            $field = null;
            $newValue = null;
            $currentPassword = null; // Hanya untuk password update

            if ($update_type === 'username') {
                $field = 'username';
                $newValue = trim($data['newUsername'] ?? '');
                if (empty($newValue)) throw new Exception('Username cannot be empty.');
                // Cek duplikasi username (opsional tapi disarankan)
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
                $stmt->execute([$newValue, $user_id]);
                if ($stmt->fetch()) throw new Exception('Username already taken.');
            } elseif ($update_type === 'fullname') {
                $field = 'fullname';
                $newValue = trim($data['newFullname'] ?? '');
                if (empty($newValue)) throw new Exception('Full name cannot be empty.');
            } elseif ($update_type === 'email') {
                $field = 'email';
                $newValue = trim($data['newEmail'] ?? '');
                if (empty($newValue) || !filter_var($newValue, FILTER_VALIDATE_EMAIL)) throw new Exception('Invalid email format or empty.');
                // Cek duplikasi email (opsional tapi disarankan)
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$newValue, $user_id]);
                if ($stmt->fetch()) throw new Exception('Email already registered.');
            } elseif ($update_type === 'password') {
                $field = 'password';
                $newValue = $data['newPassword'] ?? '';
                $currentPassword = $data['currentPassword'] ?? '';

                if (empty($newValue) || strlen($newValue) < 6) throw new Exception('New password must be at least 6 characters long.');

                // Verifikasi password lama
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $hashedCurrentPassword = $stmt->fetchColumn();

                if (!password_verify($currentPassword, $hashedCurrentPassword)) {
                    throw new Exception('Current password is incorrect.');
                }
                $newValue = password_hash($newValue, PASSWORD_DEFAULT); // Hash password baru
            } elseif ($update_type === 'forgot-password') {
                 $field = 'password';
                 $newValue = $data['newPassword'] ?? '';
                 // Ini adalah reset password, jadi tidak perlu verifikasi password lama
                 // Peringatan: Untuk keamanan, reset password tanpa verifikasi password lama
                 // atau token reset sangat tidak disarankan untuk produksi.
                 if (empty($newValue) || strlen($newValue) < 6) throw new Exception('New password must be at least 6 characters long.');
                 $newValue = password_hash($newValue, PASSWORD_DEFAULT);
            }
            // Tambahan untuk update gender, age, country (jika Anda akan membuat UI untuk ini)
            // elseif ($update_type === 'gender') {
            //     $field = 'gender';
            //     $newValue = trim($data['newGender'] ?? '');
            //     if (empty($newValue)) throw new Exception('Gender cannot be empty.');
            // } elseif ($update_type === 'age') {
            //     $field = 'age';
            //     $newValue = intval($data['newAge'] ?? 0);
            //     if ($newValue <= 0) throw new Exception('Invalid age.');
            // } elseif ($update_type === 'country') {
            //     $field = 'country';
            //     $newValue = trim($data['newCountry'] ?? '');
            //     if (empty($newValue)) throw new Exception('Country cannot be empty.');
            // } 
            else {
                throw new Exception('Invalid update type.');
            }

            // Lakukan update di database
            $stmt = $pdo->prepare("UPDATE users SET {$field} = ? WHERE id = ?");
            $stmt->execute([$newValue, $user_id]);

            $response['success'] = true;
            $response['message'] = ucfirst($update_type) . ' updated successfully!';
            
            // Kirim nilai baru kembali ke frontend (untuk password, jangan kirim hash)
            $response['new_value'] = ($field === 'password') ? '********' : $newValue; 

            // Update sesi juga
            if ($field === 'username') $_SESSION['username'] = $newValue;
            if ($field === 'fullname') $_SESSION['fullname'] = $newValue;
            if ($field === 'email') $_SESSION['user_email'] = $newValue;
            // Password tidak disimpan di sesi
        }

    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
        error_log("Profile update error ({$update_type}): " . $e->getMessage());
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response); // Baris ini yang diperbaiki
exit(); // Selalu exit setelah mengirim respons JSON
?>