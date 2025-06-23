document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const adminWrapper = document.querySelector('.admin-wrapper');
    const mainContent = document.querySelector('.main-content');

    // Fungsi buka-tutup sidebar mobile
    if (mobileMenuToggle && adminWrapper) {
        mobileMenuToggle.addEventListener('click', function() {
            adminWrapper.classList.toggle('sidebar-open');
        });
    }

    if (mainContent) {
        // Menutup sidebar jika mengklik di luar area sidebar pada mode mobile
        mainContent.addEventListener('click', function() {
            if (adminWrapper.classList.contains('sidebar-open')) {
                adminWrapper.classList.remove('sidebar-open');
            }
        });
    }

    // Fungsi untuk modal (contoh, bisa dikembangkan)
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Menutup modal jika user mengklik di luar area konten modal
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            event.target.style.display = 'none';
        }
    }
});

// Contoh fungsi konfirmasi umum yang bisa dipanggil dari HTML
// onsubmit="return confirmAction('Apakah Anda yakin?')"
function confirmAction(message) {
    return confirm(message);
}
