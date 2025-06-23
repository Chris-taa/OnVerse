<?php
session_start(); // Pastikan sesi dimulai

include '../php/db.php'; // koneksi ke database

// PERUBAHAN: Ambil ID pengguna dari sesi. Jika tidak ada, redirect atau tampilkan error.
$id = $_SESSION['user_id'] ?? null; 

if (!$id) {
    // Jika tidak ada sesi user_id, mungkin belum login
    // Redirect ke halaman login dan hentikan eksekusi script
    header('Location: login.html'); 
    exit();
}

$query = $pdo->prepare("SELECT id, username, fullname, email, photo, gender, age, creator, country, created_at FROM users WHERE id = ?");
$query->execute([$id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Kalau user tidak ditemukan di database (meskipun ada session ID, data tidak valid)
if (!$user) {
    session_destroy(); // Hancurkan sesi yang tidak valid
    header('Location: login.html'); // Redirect ke halaman login
    exit();
}

// Data pengguna yang akan digunakan di HTML dan JavaScript
// Menggunakan nilai fallback jika kolom bisa NULL di database dan tidak diisi
$photo = htmlspecialchars($user['photo'] ?? 'pp.jpg'); // Pastikan ini path yang benar ke foto default
$fullname = htmlspecialchars($user['fullname'] ?? 'Nama Lengkap Anda');
$username = htmlspecialchars($user['username'] ?? 'usernameAnda');
$email = htmlspecialchars($user['email'] ?? 'email@example.com');
$gender = htmlspecialchars($user['gender'] ?? 'Tidak Diketahui');
$age = htmlspecialchars($user['age'] ?? '-');
$status = $user['creator'] ? 'Creator' : 'User';
$country = htmlspecialchars($user['country'] ?? '-');
$createdAt = htmlspecialchars($user['created_at'] ?? '-');

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONVERSE - Edit Profile</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* --- CSS Anda yang sudah ada --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
            padding: 20px;
        }

        /* Container */
        .profile-container {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 56, 180, 0.5);
            width: 100%;
            max-width: 450px;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        /* Sections */
        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        /* Profile Picture */
        .profile-picture {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 24px;
            font-weight: bold;
            color: #666;
            border: 3px solid #f0f0f0;
            overflow: hidden;
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .edit-btn {
            background: none;
            border: none;
            color: #4285f4;
            font-size: 12px;
            cursor: pointer;
            margin-bottom: 30px;
            text-decoration: underline;
            transition: color 0.3s ease;
        }

        .edit-btn:hover {
            color: #3b78e7;
        }

        /* Profile Info */
        .profile-info {
            text-align: left;
            margin-bottom: 30px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 500;
            color: #333;
            font-size: 14px;
            flex: 1;
        }

        .info-value {
            color: #666;
            font-size: 14px;
            text-align: right;
            flex: 1;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .info-value:hover {
            color: #4285f4;
        }

        .password-value {
            font-family: monospace;
            letter-spacing: 2px;
        }

        .status-badge {
            background-color: #4CAF50;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }

        /* Buttons */
        .profile-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .back-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            background-color: #e0e0e0;
            color: #666;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .confirm-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            background-color: #4285f4;
            color: white;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .back-btn:hover {
            background-color: #d0d0d0;
            transform: translateY(-1px);
        }

        .confirm-btn:hover {
            background-color: #3b78e7;
            transform: translateY(-1px);
        }

        /* Edit Form Styles */
        .form-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .input-field {
            width: 100%;
            padding: 15px 20px;
            margin-bottom: 20px;
            border: none;
            border-radius: 25px;
            background-color: #f0f0f0;
            font-size: 16px;
            outline: none;
            transition: background-color 0.3s;
            text-align: center;
        }

        .input-field:focus {
            background-color: #e8e8e8;
        }

        .input-field::placeholder {
            color: #999;
        }

        /* Photo Upload Styles */
        .photo-container {
            margin-bottom: 30px;
        }

        .photo-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #f0f0f0;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 3px solid #e0e0e0;
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-preview .placeholder {
            color: #999;
            font-size: 48px;
        }

        .file-input {
            display: none;
        }

        .file-button {
            width: 100%;
            padding: 15px 20px;
            border: none;
            border-radius: 25px;
            background-color: #f0f0f0;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            color: #666;
            margin-bottom: 20px;
        }

        .file-button:hover {
            background-color: #e8e8e8;
        }

        .file-button.selected {
            background-color: #e8f5e8;
            color: #2e7d32;
        }

        .link {
            display: block;
            color: #4285f4;
            font-size: 14px;
            text-decoration: underline;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .link:hover {
            color: #3b78e7;
        }

        @media (max-width: 480px) {
            .profile-container {
                padding: 30px 20px;
                margin: 10px;
            }

            .profile-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .back-btn, .confirm-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="profile-container">
    <div class="section active" id="profile-page">
        <div class="profile-picture" id="mainProfilePicture">
            <img src="../IMG/<?php echo $photo; ?>" alt="Profile Photo">
        </div>
        <button class="edit-btn" onclick="showSection('upload-photo')">Edit ></button>
        
        <div class="profile-info">
            <div class="info-row">
                <span class="info-label">Full Name</span>
                <span class="info-value" id="displayFullname" onclick="showSection('update-fullname')">
                    <?php echo $fullname; ?> >
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Username</span>
                <span class="info-value" id="displayUsername" onclick="showSection('update-username')">
                    <?php echo $username; ?> >
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value" id="displayEmail" onclick="showSection('edit-email')">
                    <?php echo $email; ?> >
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Password</span>
                <span class="info-value password-value" onclick="showSection('change-password')">
                    ******** >
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Gender</span>
                <span class="info-value" id="displayGender">
                    <?php echo $gender; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Age</span>
                <span class="info-value" id="displayAge">
                    <?php echo $age; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">
                    <span class="status-badge" id="displayStatus">
                        <?php echo $status; ?>
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Country</span>
                <span class="info-value" id="displayCountry">
                    <?php echo $country; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Created at</span>
                <span class="info-value" id="displayCreatedAt">
                    <?php echo $createdAt; ?>
                </span>
            </div>
        </div>

        <div class="profile-buttons">
            <button class="back-btn" onclick="goBack()">Back</button>
            <button class="confirm-btn" onclick="confirmProfile()">Confirm</button>
        </div>
    </div>

    <div class="section" id="upload-photo">
        <h2 class="form-title">Upload Photo</h2>
        <div class="photo-container">
            <div class="photo-preview" id="photoPreview">
                <img src="../IMG/<?php echo $photo; ?>" alt="Current Profile Photo">
            </div>
            <input type="file" id="photoInput" class="file-input" accept="image/*">
            <button type="button" class="file-button" onclick="document.getElementById('photoInput').click()">
                Choose Photo
            </button>
        </div>
        <div class="profile-buttons">
            <button class="back-btn" onclick="showSection('profile-page')">Back</button>
            <button class="confirm-btn" onclick="confirmChange('photo')">Confirm</button>
        </div>
    </div>

    <div class="section" id="update-username">
        <h2 class="form-title">Update Username</h2>
        <input type="text" class="input-field" placeholder="Current Username" readonly id="currentUsernameInput" value="<?php echo $username; ?>">
        <input type="text" class="input-field" placeholder="New Username" id="newUsername">
        <div class="profile-buttons">
            <button class="back-btn" onclick="showSection('profile-page')">Back</button>
            <button class="confirm-btn" onclick="confirmChange('username')">Confirm</button>
        </div>
    </div>

    <div class="section" id="update-fullname">
        <h2 class="form-title">Update Full Name</h2>
        <input type="text" class="input-field" placeholder="Current Full Name" readonly id="currentFullnameInput" value="<?php echo $fullname; ?>">
        <input type="text" class="input-field" placeholder="New Full Name" id="newFullname">
        <div class="profile-buttons">
            <button class="back-btn" onclick="showSection('profile-page')">Back</button>
            <button class="confirm-btn" onclick="confirmChange('fullname')">Confirm</button>
        </div>
    </div>

    <div class="section" id="edit-email">
        <h2 class="form-title">Update Email</h2>
        <input type="email" class="input-field" placeholder="Current Email" readonly id="currentEmailInput" value="<?php echo $email; ?>">
        <input type="email" class="input-field" placeholder="New Email" id="newEmail">
        <div class="profile-buttons">
            <button class="back-btn" onclick="showSection('profile-page')">Back</button>
            <button class="confirm-btn" onclick="confirmChange('email')">Confirm</button>
        </div>
    </div>

    <div class="section" id="change-password">
        <h2 class="form-title">Change Password</h2>
        <input type="password" class="input-field" placeholder="Current Password" id="currentPassword">
        <input type="password" class="input-field" placeholder="New Password" id="newPassword">
        <input type="password" class="input-field" placeholder="Confirm New Password" id="confirmNewPassword">
        <a href="#" class="link" onclick="showSection('forgot-password')">Forget Password?</a>
        <div class="profile-buttons">
            <button class="back-btn" onclick="showSection('profile-page')">Back</button>
            <button class="confirm-btn" onclick="confirmChange('password')">Confirm</button>
        </div>
    </div>

    <div class="section" id="forgot-password">
        <h2 class="form-title">Reset Password</h2>
        <input type="password" class="input-field" placeholder="New Password" id="forgotPassword">
        <input type="password" class="input-field" placeholder="Confirm New Password" id="confirmForgotPassword">
        <div class="profile-buttons">
            <button class="back-btn" onclick="showSection('change-password')">Back</button>
            <button class="confirm-btn" onclick="confirmChange('forgot-password')">Confirm</button>
        </div>
    </div>
</div>

<script>
    // Data user yang diambil dari PHP
    const userData = {
        id: <?php echo json_encode($user['id']); ?>,
        photo: <?php echo json_encode($user['photo'] ?? 'pp.jpg'); ?>,
        fullname: <?php echo json_encode($user['fullname']); ?>,
        username: <?php echo json_encode($user['username']); ?>,
        email: <?php echo json_encode($user['email']); ?>,
        gender: <?php echo json_encode($user['gender']); ?>,
        age: <?php echo json_encode($user['age']); ?>,
        creator: <?php echo json_encode((bool)$user['creator']); ?>,
        country: <?php echo json_encode($user['country']); ?>,
        created_at: <?php echo json_encode($user['created_at']); ?>
    };

    // State management
    const state = {
        currentSection: 'profile-page',
    };

    // Fungsi untuk menampilkan section yang benar
    function showSection(sectionId) {
        document.querySelectorAll('.section').forEach(section => {
            section.classList.remove('active');
        });
        document.getElementById(sectionId).classList.add('active');
        state.currentSection = sectionId;
        window.location.hash = sectionId; // Update URL hash

        // Set current values in edit forms when switching to them
        if (sectionId === 'update-username') {
            document.getElementById('currentUsernameInput').value = userData.username;
            document.getElementById('newUsername').value = ''; // Clear new input
        } else if (sectionId === 'update-fullname') {
            document.getElementById('currentFullnameInput').value = userData.fullname;
            document.getElementById('newFullname').value = ''; // Clear new input
        } else if (sectionId === 'edit-email') {
            document.getElementById('currentEmailInput').value = userData.email;
            document.getElementById('newEmail').value = ''; // Clear new input
        } else if (sectionId === 'change-password') {
            document.getElementById('currentPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmNewPassword').value = '';
        } else if (sectionId === 'forgot-password') {
            document.getElementById('forgotPassword').value = '';
            document.getElementById('confirmForgotPassword').value = '';
        } else if (sectionId === 'upload-photo') {
             // Reset photo preview if navigating away and back without saving
             const currentPhoto = userData.photo || 'pp.jpg';
             document.getElementById('photoPreview').innerHTML = `<img src="../IMG/${currentPhoto}" alt="Current Profile Photo">`;
             document.getElementById('photoInput').value = ''; // Clear selected file
             document.querySelector('.file-button').textContent = 'Choose Photo';
             document.querySelector('.file-button').classList.remove('selected');
        }
    }

    // Fungsi untuk kembali (disesuaikan agar konsisten)
    function goBack() {
        if (state.currentSection !== 'profile-page') {
            showSection('profile-page');
        } else {
            // Jika sudah di profile-page utama, kembali ke halaman sebelumnya di history
            // atau redirect ke landing.html
            if (document.referrer) {
                
                window.location.href = 'landing.php';
            } else {
                window.location.href = 'landing.php';
            }
        }
    }

    // Fungsi untuk mengkonfirmasi perubahan
    async function confirmChange(type) {
        let payload = {};
        let url = '../php/update_profile.php';
        let customHeaders = {
            'X-Update-Type': type // Kirim tipe update sebagai custom header
        };
        let formData = null; // Untuk upload file

        // Validasi dan siapkan payload
        if (type === 'photo') {
            const fileInput = document.getElementById('photoInput');
            if (!fileInput.files.length) {
                alert('Please select a photo to upload!');
                return;
            }
            formData = new FormData();
            formData.append('photo', fileInput.files[0]);
            // Tidak perlu 'Content-Type': 'application/json' untuk FormData
            customHeaders = { 'X-Update-Type': type }; // Hapus content-type jika ada sebelumnya
        } else if (type === 'username') {
            const newUsername = document.getElementById('newUsername').value.trim();
            if (!newUsername) {
                alert('New Username cannot be empty!');
                return;
            }
            payload = { newUsername: newUsername };
        } else if (type === 'fullname') {
            const newFullname = document.getElementById('newFullname').value.trim();
            if (!newFullname) {
                alert('New Full Name cannot be empty!');
                return;
            }
            payload = { newFullname: newFullname };
        } else if (type === 'email') {
            const newEmail = document.getElementById('newEmail').value.trim();
            if (!newEmail || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(newEmail)) {
                alert('Please enter a valid email!');
                return;
            }
            payload = { newEmail: newEmail };
        } else if (type === 'password') {
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmNewPassword = document.getElementById('confirmNewPassword').value;

            if (!currentPassword || !newPassword || !confirmNewPassword) {
                alert('Please fill in all password fields!');
                return;
            }
            if (newPassword.length < 6) {
                alert('New password must be at least 6 characters long!');
                return;
            }
            if (newPassword !== confirmNewPassword) {
                alert('New passwords do not match!');
                return;
            }
            payload = { currentPassword: currentPassword, newPassword: newPassword };
        } else if (type === 'forgot-password') {
            const newPassword = document.getElementById('forgotPassword').value;
            const confirmForgotPassword = document.getElementById('confirmForgotPassword').value;

            if (!newPassword || !confirmForgotPassword) {
                alert('Please fill in all fields!');
                return;
            }
            if (newPassword.length < 6) {
                alert('New password must be at least 6 characters long!');
                return;
            }
            if (newPassword !== confirmForgotPassword) {
                alert('Passwords do not match!');
                return;
            }
            payload = { newPassword: newPassword };
        } else {
            alert('Invalid change type!');
            return;
        }

        // Kirim permintaan ke PHP
        try {
            const fetchOptions = {
                method: 'POST',
                headers: customHeaders // Custom header untuk tipe update
            };

            if (formData) {
                fetchOptions.body = formData; // Gunakan FormData untuk file upload
            } else {
                fetchOptions.headers['Content-Type'] = 'application/json'; // Untuk data JSON
                fetchOptions.body = JSON.stringify(payload);
            }

            const response = await fetch(url, fetchOptions);
            const data = await response.json();

            if (data.success) {
                alert(data.message);
                // Perbarui data di client-side (userData object dan tampilan)
                if (type === 'photo') {
                    userData.photo = data.new_value; // nama file foto baru
                    document.getElementById('mainProfilePicture').querySelector('img').src = `../IMG/${userData.photo}`;
                    // Jika ada gambar preview di photoPreview (saat di section upload-photo), perbarui juga
                    const photoPreviewImg = document.getElementById('photoPreview').querySelector('img');
                    if (photoPreviewImg) photoPreviewImg.src = `../IMG/${userData.photo}`;
                } else if (type === 'username') {
                    userData.username = data.new_value;
                    document.getElementById('displayUsername').textContent = userData.username + ' >';
                    // Opsional: Perbarui sessionStorage untuk username di navbar/sidebar
                    sessionStorage.setItem('userName', userData.username);
                } else if (type === 'fullname') {
                    userData.fullname = data.new_value;
                    document.getElementById('displayFullname').textContent = userData.fullname + ' >';
                    // Opsional: Perbarui sessionStorage untuk fullname di navbar/sidebar
                    sessionStorage.setItem('userFullname', userData.fullname);
                } else if (type === 'email') {
                    userData.email = data.new_value;
                    document.getElementById('displayEmail').textContent = userData.email + ' >';
                }
                // Password tidak perlu diupdate di tampilan info-value atau state.userData.password

                // Kembali ke halaman profil utama setelah sukses
                showSection('profile-page');
                clearInputs(); // Kosongkan input form setelah berhasil update
                
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error during profile update:', error);
            alert('An error occurred during update. Please try again.');
        }
    }

    // Fungsi untuk "Confirm Profile" (tetap seperti sebelumnya, tidak mengirim ke server)
    function confirmProfile() {
        alert('Profile confirmed!');
        goBack(); // Kembali ke halaman sebelumnya
    }

    // Fungsi untuk mengosongkan input form
    function clearInputs() {
        document.querySelectorAll('.input-field:not([readonly])').forEach(input => {
            input.value = '';
        });
        // Reset photo upload state
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');
        const fileButton = document.querySelector('.file-button');
        
        if (photoInput) {
            photoInput.value = '';
            // Kembalikan preview ke gambar profil saat ini (userData.photo) atau placeholder
            const currentPhoto = userData.photo || 'pp.jpg';
            photoPreview.innerHTML = `<img src="../IMG/${currentPhoto}" alt="Profile Photo">`;
            fileButton.textContent = 'Choose Photo';
            fileButton.classList.remove('selected');
        }
    }

    // Photo upload functionality (Preview)
    document.getElementById('photoInput').addEventListener('change', handlePhotoUpload);

    function handlePhotoUpload(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('photoPreview');
        const button = document.querySelector('.file-button');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = `<img src="${e.target.result}" alt="Profile Photo">`;
            };
            reader.readAsDataURL(file);
            button.textContent = file.name;
            button.classList.add('selected');
        } else {
            // Jika file dibatalkan, kembali ke gambar profil saat ini dari userData
            const currentPhoto = userData.photo || 'pp.jpg';
            preview.innerHTML = `<img src="../IMG/${currentPhoto}" alt="Profile Photo">`;
            button.textContent = 'Choose Photo';
            button.classList.remove('selected');
        }
    }

    // Inisialisasi saat DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        // Handle URL hash on load
        const hash = window.location.hash.substring(1);
        if (hash && document.getElementById(hash)) {
            showSection(hash);
        } else {
            showSection('profile-page'); // Default to main profile page
        }
        
        // Inisialisasi nilai input current di form edit
        document.getElementById('currentUsernameInput').value = userData.username;
        document.getElementById('currentFullnameInput').value = userData.fullname;
        document.getElementById('currentEmailInput').value = userData.email;
        // Inisialisasi preview foto ke foto user saat ini
        const mainProfilePhoto = document.getElementById('mainProfilePicture').querySelector('img').src;
        document.getElementById('photoPreview').innerHTML = `<img src="${mainProfilePhoto}" alt="Current Profile Photo">`;
    });

    // Handle browser navigation (back/forward button)
    window.addEventListener('hashchange', function() {
        const hash = window.location.hash.substring(1);
        if (hash && document.getElementById(hash)) {
            showSection(hash);
        } else {
            showSection('profile-page'); // Fallback jika hash tidak valid
        }
    });
</script>
</body>
</html>