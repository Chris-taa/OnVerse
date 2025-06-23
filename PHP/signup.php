<?php
// php/signup.php
session_start();
header('Content-Type: application/json');

include 'db.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $fullname = trim($data['fullname'] ?? ''); // BARU: Ambil fullname
    $username = trim($data['username'] ?? ''); // PERUBAHAN: Ambil username
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';
    $age = $data['age'] ?? null;
    $gender = $data['gender'] ?? null;
    $country = $data['country'] ?? null;

    // Basic validation
    // PERUBAHAN: Validasi sekarang termasuk username dan fullname
    if (empty($fullname) || empty($username) || empty($email) || empty($password)) {
        $response['message'] = 'Full name, username, email, and password are required.';
        echo json_encode($response);
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format.';
        echo json_encode($response);
        exit();
    }
    if (strlen($password) < 6) {
        $response['message'] = 'Password must be at least 6 characters long.';
        echo json_encode($response);
        exit();
    }

    try {
        // PERUBAHAN: Check if email OR username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $response['message'] = 'Email or username already registered.';
            echo json_encode($response);
            exit();
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // DEFAULT VALUES for new user:
        $final_creator_status = 0;
        $final_verified_status = 0; // Default: Not verified upon signup
        $final_role = 'reader'; // Set role based on signup type
        $default_photo = 'pp.jpg'; // Default profile picture
        $coin = 0;   // Default coins
        $creator = 0; // Default: regular user
        $coin = 0;   // Default: 0 coins

        
        // Insert new user
        // PERUBAHAN DI SINI: Tambahkan 'fullname' dan ubah 'name' menjadi 'username'
        $stmt = $pdo->prepare(
            "INSERT INTO users (username, fullname, age, email, photo, password, role, gender, creator, verified, country, coin, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
        );
         $stmt->execute([
            $username,
            $fullname,
            $age,
            $email,
            $default_photo, // Masukkan default photo
            $hashedPassword,
            $final_role, // Role yang ditetapkan
            $gender,
            $final_creator_status, // Status creator
            $final_verified_status, // Status verified
            $country,
            $coin
        ]);

// Auto-login after successful registration
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $username;
        $_SESSION['fullname'] = $fullname;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_photo'] = $default_photo; // Simpan default photo ke sesi
        $_SESSION['is_creator'] = (bool)$final_creator_status;
        $_SESSION['verified'] = (bool)$final_verified_status; // Simpan verified status ke sesi
        $_SESSION['userRole'] = $final_role; // Simpan role ke sesi

        $response['success'] = true;
        $response['message'] = 'Registration successful! You are now logged in.';
        $response['user'] = [
            'id' => $_SESSION['user_id'],
            'username' => $username,
            'fullname' => $fullname,
            'email' => $email,
            'photo' => $default_photo, // Kirim default photo
            'is_creator' => (bool)$final_creator_status,
            'verified' => (bool)$final_verified_status, // Kirim verified status
            'role' => $final_role // Kirim role
        ];

    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
        error_log("Signup error: " . $e->getMessage());
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>