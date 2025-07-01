// components/sidebar.js

/**
 * Creates and returns a sidebar HTML element based on login status and user role.
 * @param {boolean} isLoggedIn - True if the user is logged in, false otherwise.
 * @param {string|null} userRole - 'reader', 'author', 'admin', or null if not logged in.
 * @param {boolean} userVerified - True if the user's creator account is verified, false otherwise.
 * @param {function} closeNavFunction - The function to call when the close button is clicked.
 * @returns {HTMLElement} The created sidebar div element.
 */
function createSidebar(isLoggedIn, userRole, userVerified, closeNavFunction) { // TAMBAH userVerified
  const sidebarDiv = document.createElement("div");
  sidebarDiv.id = "mySidebar";
  sidebarDiv.classList.add("sidebar");

  // Close button
  const closeBtn = document.createElement("a");
  closeBtn.href = "javascript:void(0)";
  closeBtn.classList.add("closebtn");
  closeBtn.innerHTML = "&times;";
  closeBtn.onclick = closeNavFunction;
  sidebarDiv.appendChild(closeBtn);

  let linksData = [];

  // Links for users who are NOT logged in
  if (!isLoggedIn) {
    linksData = [
      { text: "View All Works", href: "view-works.html" },
      { text: "This Week's Popular", href: "#" },
      { text: "Premium", href: "#" },
      { text: "Community", href: "community-home.php" },
      { text: "Login / Register", href: "login.html" }, // Sesuaikan ke LoginPage.html
    ];
  } else {
    // Links for users who ARE logged in (regular or creator)
    linksData = [
      { text: "View All Works", href: "view-works.html" },
      { text: "Daily Login", href: "#" },
      { text: "Daily Milestones", href: "#openMilestonePopupBtn", id: "openMilestonePopupBtn" },
      { text: "This Week's Popular", href: "#" },
      { text: "Premium", href: "#" },
      { text: "Community", href: "../community/community-home.php" },
      // Link "Publish" selalu ada jika logged in, tetapi mungkin diblokir di PHP jika belum verified
      { text: "Publish", href: "../HTML/create-karya.html" }, 
    ];

    // Jika userRole adalah 'author' (berarti creator = 1 di DB) DAN sudah diverifikasi
    if (userRole === "author" && userVerified) { // PERBAIKAN: Cek userRole DAN userVerified
      linksData.push(
        { text: "Creator Dashboard", href: "../Creator's/creator-dashboard.php" }
      );
    } else {
      // Regular user atau Creator yang belum diverifikasi
      linksData.push(
        { text: "My Account", href: "ProfilePage.php" }
      );
    }
    // Add logout link for ALL logged-in users
    linksData.push({ text: "Logout", href: "../php/logout.php" });
  }

  // Append links to the sidebar
  linksData.forEach((linkData) => {
    const link = document.createElement("a");
    link.href = linkData.href;
    link.textContent = linkData.text;
    if (linkData.id) {
      link.id = linkData.id;
    }
    if (linkData.text === "Logout") {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        fetch(linkData.href)
          .then(() => {
            sessionStorage.removeItem("isLoggedIn");
            sessionStorage.removeItem("userRole");
            sessionStorage.removeItem("userVerified"); // Hapus juga userVerified
            sessionStorage.removeItem("userName");
            sessionStorage.removeItem("userFullname");
            sessionStorage.removeItem("user_id"); // Hapus user_id
            sessionStorage.removeItem("user_email"); // Hapus user_email
            // userPhoto jika Anda menyimpannya
            
            window.location.href = "landing.php";
          })
          .catch((error) => console.error("Logout error:", error));
      });
    }
    sidebarDiv.appendChild(link);
  });

  return sidebarDiv;
}

