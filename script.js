// Fungsi untuk menampilkan modal setelah 10 detik
setTimeout(function() {
    document.getElementById('notification-modal').style.display = 'flex';
}, 10000);

// Event listener untuk tombol tutup modal
document.querySelector('.cancel-modal-btn').addEventListener('click', function() {
    document.getElementById('notification-modal').style.display = 'none';
});

// Event listener untuk tombol login di header
document.querySelector('.login-btn').addEventListener('click', function() {
    window.location.href = 'login.html';
});

// Event listener untuk tombol login
document.querySelector('.login-modal-btn').addEventListener('click', function() {
    // Implementasi login bisa ditambahkan di sini
    document.getElementById('notification-modal').style.display = 'none';
});

// Event listener untuk tombol signup di header
document.querySelector('.signup-btn').addEventListener('click', function() {
    window.location.href = 'signup.html';
});

// Event listener untuk tombol top up coin
document.querySelector('.topup-btn').addEventListener('click', function() {
    window.location.href = 'topup.html';
});

// Event listener untuk tombol buat karya
document.querySelector('.buatkarya-btn').addEventListener('click', function() {
    window.location.href = 'buatkarya.html';
});