document.addEventListener('DOMContentLoaded', () => {
  const openMilestonePopupBtn = document.getElementById('openMilestonePopupBtn');
  const milestonePopup = document.getElementById('milestonePopup');
  const closeButton = milestonePopup.querySelector('.close-button');

  const sidebar = document.getElementById('mySidebar');
  const sidebarOverlay = document.getElementById('sidebar-overlay');

  // Function to open the pop-up
  if (openMilestonePopupBtn) {
    openMilestonePopupBtn.addEventListener('click', (event) => {
      event.preventDefault();

      // Show the pop-up overlay (it will animate from opacity 0 to 1)
      milestonePopup.style.display = 'flex';
      // Add 'active' class to trigger content animation
      // Use a small timeout to allow display:flex to render before transition starts
      setTimeout(() => {
        milestonePopup.classList.add('active');
      }, 10); // Small delay, adjust if needed

      // Close the sidebar when "Milestone Harian" is clicked
      if (sidebar) {
        sidebar.style.width = '0';
      }
      if (sidebarOverlay) {
        sidebarOverlay.style.display = 'none';
      }
    });
  }

  // Function to close the pop-up when the close button is clicked
  if (closeButton) {
    closeButton.addEventListener('click', () => {
      // Remove 'active' class to trigger reverse animation
      milestonePopup.classList.remove('active');
      // Hide the pop-up overlay after the animation completes
      milestonePopup.addEventListener('transitionend', function handler() {
        milestonePopup.style.display = 'none';
        milestonePopup.removeEventListener('transitionend', handler); // Remove listener to prevent multiple calls
      });
    });
  }

  // Function to close the pop-up when clicking outside the pop-up content
  if (milestonePopup) {
    milestonePopup.addEventListener('click', (event) => {
      if (event.target === milestonePopup) {
        // Remove 'active' class to trigger reverse animation
        milestonePopup.classList.remove('active');
        // Hide the pop-up overlay after the animation completes
        milestonePopup.addEventListener('transitionend', function handler() {
          milestonePopup.style.display = 'none';
          milestonePopup.removeEventListener('transitionend', handler);
        });
      }
    });
  }
});