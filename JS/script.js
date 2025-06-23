// script.js

// Function to initialize all main page features (excluding component-specific ones)
function initializeLandingPageFeatures() {
    // ========== HIGHLIGHT HARI SEKARANG ==========
    const dayButtons = document.querySelectorAll(".content .days .day");
    if (dayButtons.length > 0) {
        const today = new Date().toLocaleDateString("en-US", { weekday: "long" });

        dayButtons.forEach((button) => {
            const day = button.getAttribute("data-day");
            if (day === today) {
                button.classList.add("active");
            }
            // Remove existing listener to prevent duplicates if this runs multiple times
            button.removeEventListener("click", handleDayButtonClick);
            button.addEventListener("click", handleDayButtonClick);
        });

        function handleDayButtonClick() {
            dayButtons.forEach((btn) => btn.classList.remove("active"));
            this.classList.add("active");
        }
    }

    // You can add more initialization for other sections of landing.html here if needed.
}


// ==================== Fungsi untuk menginisialisasi fitur Navbar (toggle & hamburger) ========================
// Fungsi ini harus global agar bisa dipanggil dari HTML utama setelah navbar dimuat.
function initializeNavbarFeatures() {
    const mySidebar = document.getElementById("mySidebar");
    const sidebarOverlay = document.getElementById("sidebar-overlay");

    if (!mySidebar || !sidebarOverlay) {
        console.warn("Sidebar elements not found. Sidebar functionality might not work.");
        return;
    }

    // openNav dan closeNav sudah didefinisikan secara global di inline script landing.html

    const hamburgerMenu = document.getElementById("hambuger-menu");
    if (hamburgerMenu) {
        // Remove existing listener to prevent duplicates
        hamburgerMenu.removeEventListener("click", toggleSidebar);
        hamburgerMenu.addEventListener("click", toggleSidebar);
    } else {
        console.warn("Hamburger menu button not found.");
    }

    function toggleSidebar(e) {
        e.preventDefault();
        // Memanggil fungsi global openNav/closeNav
        if (mySidebar.classList.contains("active")) {
            window.closeNav();
        } else {
            window.openNav();
        }
    }

    // close button di sidebar (yang dibuat secara dinamis oleh sidebar.js)
    // event listener untuk tombol close sidebar sudah ditangani oleh createSidebar di sidebar.js,
    // yang memanggil closeNavFunction (yaitu window.closeNav). Jadi ini tidak perlu di sini.
    /*
    const closeButton = document.querySelector("#mySidebar .closebtn");
    if (closeButton) {
        closeButton.removeEventListener("click", window.closeNav);
        closeButton.addEventListener("click", window.closeNav);
    }
    */

    if (sidebarOverlay) {
        sidebarOverlay.removeEventListener("click", window.closeNav); // Use window.closeNav
        sidebarOverlay.addEventListener("click", window.closeNav);
    }

    // ========== TOGGLE IKON & BACKGROUND ==========
    const toggleButton = document.getElementById("toggle-button");
    let toggleState = "left"; // Default

    if (toggleButton) {
        toggleButton.removeEventListener("click", handleToggleButtonClick);
        toggleButton.addEventListener("click", handleToggleButtonClick);
    } else {
        console.warn("Toggle button not found.");
    }

    function handleToggleButtonClick(e) {
        e.preventDefault();
        toggleState = toggleState === "left" ? "right" : "left";
        toggleButton.innerHTML = `<i data-feather="toggle-${toggleState}"></i>`;
        feather.replace(); // Re-initialize feather icon for the toggle button itself
        if (document.body) {
            if (toggleState === "right") {
                document.body.style.backgroundImage = "url('../IMG/backgroundhitam.png')";
            } else {
                document.body.style.backgroundImage = "url('../IMG/background.png')";
            }
        }
    }

    // ==================== Script untuk Navbar Region (language menu) ========================
    const languageMenu = document.getElementById("languageMenu");
    const showButton = document.getElementById("showButton");

    let hasInteracted = false;
    let hideTimeout;

    // Clear existing listeners before adding new ones
    if (languageMenu) {
        languageMenu.removeEventListener("mouseover", handleLanguageMenuMouseOver);
        languageMenu.removeEventListener("mouseout", handleLanguageMenuMouseOut);
    }
    if (showButton) {
        showButton.removeEventListener("click", handleShowButtonClick);
    }

    if (languageMenu && showButton) {
        // Initial state: hide after 3 seconds if no interaction
        clearTimeout(hideTimeout);
        hideTimeout = setTimeout(() => {
            if (!hasInteracted) {
                languageMenu.style.display = "none";
                showButton.style.display = "block";
            }
        }, 3000);

        languageMenu.addEventListener("mouseover", handleLanguageMenuMouseOver);
        languageMenu.addEventListener("mouseout", handleLanguageMenuMouseOut);
        showButton.addEventListener("click", handleShowButtonClick);

        function handleLanguageMenuMouseOver() {
            hasInteracted = true;
            clearTimeout(hideTimeout);
            languageMenu.style.display = "block";
            showButton.style.display = "none";
        }

        function handleLanguageMenuMouseOut() {
            hasInteracted = false;
            hideTimeout = setTimeout(() => {
                if (!hasInteracted) {
                    languageMenu.style.display = "none";
                    showButton.style.display = "block";
                }
            }, 500);
        }

        function handleShowButtonClick() {
            languageMenu.style.display = "block";
            showButton.style.display = "none";
            hasInteracted = false;

            clearTimeout(hideTimeout);
            hideTimeout = setTimeout(() => {
                if (!hasInteracted) {
                    languageMenu.style.display = "none";
                    showButton.style.display = "block";
                }
            }, 3000);
        }
    } else {
        console.warn("Language menu or show button not found in initializeNavbarFeatures.");
    }

    // Cek jika pengguna login untuk mengupdate nama dan foto profil
    const isLoggedIn = sessionStorage.getItem('isLoggedIn') === 'true';
    if (isLoggedIn) {
        const usernameElement = document.querySelector('.navbar .username'); // Gunakan class selector
        const profilePicElement = document.querySelector('.navbar .profile-pic'); // Gunakan class selector

        const userName = sessionStorage.getItem('userName');
        const userProfilePic = sessionStorage.getItem('userProfilePic') || '../IMG/cover.jpg'; // Gambar default

        if (usernameElement && userName) {
            usernameElement.textContent = userName;
        }

        if (profilePicElement) {
            profilePicElement.src = userProfilePic;
        }
    }
}

// Tidak ada lagi DOMContentLoaded di sini. Fungsi-fungsi ini bersifat global.