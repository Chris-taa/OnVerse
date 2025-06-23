// JS/app.js

document.addEventListener('DOMContentLoaded', () => {
  const sidebarContainer = document.getElementById('sidebar-container');
  const sidebarOverlay = document.getElementById('sidebar-overlay');
  const openSidebarBtn = document.getElementById('openSidebarBtn');

  // Define your closeNav and openNav functions globally
  window.closeNav = function() {
    const sidebar = document.getElementById("mySidebar");
    if (sidebar) {
      sidebar.style.width = "0";
    }
    document.getElementById("sidebar-overlay").style.display = "none";
  };

  window.openNav = function() {
    const sidebar = document.getElementById("mySidebar");
    if (sidebar) {
      sidebar.style.width = "250px";
    }
    document.getElementById("sidebar-overlay").style.display = "block";
  };

  // --- LOGIC TO DETERMINE USER STATUS ---
  // Dapatkan status login dan peran dari Session Storage (atau Local Storage)
  let isLoggedIn = sessionStorage.getItem('isLoggedIn') === 'true';
  let userRole = sessionStorage.getItem('userRole'); // 'creator', 'regular', or null

  // Function to update session storage and reload sidebar
  window.updateLoginStatus = function(loggedIn, role) {
    sessionStorage.setItem('isLoggedIn', loggedIn);
    sessionStorage.setItem('userRole', role);
    isLoggedIn = loggedIn;
    userRole = role;
    loadSidebar(); // Reload sidebar based on new status
  };

  // Add a logout link in your sidebar (or elsewhere) and connect it
  // For demonstration, let's add a "Logout" link to the sidebar
  // This will be handled in sidebar.js by adding a logout option for logged-in users.

  let currentSidebarInstance = null;

  function loadSidebar() {
    if (currentSidebarInstance) {
      sidebarContainer.innerHTML = '';
    }

    currentSidebarInstance = createSidebar(isLoggedIn, userRole, window.closeNav);
    sidebarContainer.appendChild(currentSidebarInstance);

    feather.replace();

    // Re-attach event listener for milestone popup if user is logged in
    if (isLoggedIn) {
      const openMilestonePopupBtn = document.getElementById('openMilestonePopupBtn');
      if (openMilestonePopupBtn) {
        openMilestonePopupBtn.addEventListener('click', (event) => {
          event.preventDefault();
          const milestonePopup = document.getElementById('milestonePopup');
          if (milestonePopup) {
            milestonePopup.style.display = 'flex';
          }
        });
      }

      const closeMilestonePopupBtn = document.querySelector('#milestonePopup .close-button');
      if (closeMilestonePopupBtn) {
        closeMilestonePopupBtn.addEventListener('click', () => {
          document.getElementById('milestonePopup').style.display = 'none';
        });
      }
      const milestonePopupOverlay = document.getElementById('milestonePopup');
      if (milestonePopupOverlay) {
        milestonePopupOverlay.addEventListener('click', (e) => {
          if (e.target === milestonePopupOverlay) {
            milestonePopupOverlay.style.display = 'none';
          }
        });
      }
    }
  }

  loadSidebar(); // Initial load of the sidebar

  if (openSidebarBtn) {
    openSidebarBtn.addEventListener('click', window.openNav);
  }
  sidebarOverlay.addEventListener('click', window.closeNav);
});