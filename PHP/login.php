<?php
// php/login.php
session_start();
header('Content-Type: application/json');

include 'db.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($email) || empty($password)) {
        $response['message'] = 'Email and password are required.';
        echo json_encode($response);
        exit();
    }

    try {
        // PASTIKAN AMBIL KOLOM 'role' DAN 'verified'
        $stmt = $pdo->prepare("SELECT id, username, fullname, email, photo, password, role, creator, verified FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array

        if ($user && password_verify($password, $user['password'])) {
            // Login sukses
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_photo'] = $user['photo']; // Simpan foto ke sesi
            $_SESSION['is_creator'] = (bool)$user['creator'];
            $_SESSION['verified'] = (bool)$user['verified']; // Simpan verified status ke sesi
            $_SESSION['userRole'] = $user['role']; // Simpan role ke sesi

            $response['success'] = true;
            $response['message'] = 'Login successful!';
            $response['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'fullname' => $user['fullname'],
                'email' => $user['email'],
                'photo' => $user['photo'], // Kirim juga photo
                'is_creator' => (bool)$user['creator'],
                'verified' => (bool)$user['verified'], // Kirim verified status
                'role' => $user['role'] // Kirim role
            ];
        } else {
            $response['message'] = 'Invalid email or password.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
        error_log("Login error: " . $e->getMessage());
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
exit();
?>