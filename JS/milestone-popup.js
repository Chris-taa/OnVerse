// milestone-popup.js

document.addEventListener('DOMContentLoaded', () => {
    // These elements might not exist immediately if loaded dynamically.
    // Ensure they are selected after the DOM is fully constructed and dynamic elements are added.
    const milestonePopup = document.getElementById('milestonePopup');
    const closeButton = milestonePopup ? milestonePopup.querySelector('.close-button') : null;

    // Function to initialize milestone popup listeners
    // This function can be called after the sidebar is rendered to ensure the button exists
    function initializeMilestonePopup() {
        const openMilestonePopupBtn = document.getElementById('openMilestonePopupBtn');
        const sidebar = document.getElementById('mySidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        if (openMilestonePopupBtn) {
            openMilestonePopupBtn.removeEventListener('click', handleOpenMilestonePopup); // Prevent duplicate listeners
            openMilestonePopupBtn.addEventListener('click', handleOpenMilestonePopup);
        }
    }

    function handleOpenMilestonePopup(event) {
        event.preventDefault();
        const milestonePopup = document.getElementById('milestonePopup');
        const sidebar = document.getElementById('mySidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        if (milestonePopup) {
            milestonePopup.style.display = 'flex';
            setTimeout(() => {
                milestonePopup.classList.add('active');
            }, 10);
        }

        if (sidebar) {
            sidebar.style.width = '0';
        }
        if (sidebarOverlay) {
            sidebarOverlay.style.display = 'none';
        }
    }

    // Function to close the pop-up when the close button is clicked
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            if (milestonePopup) {
                milestonePopup.classList.remove('active');
                milestonePopup.addEventListener('transitionend', function handler() {
                    milestonePopup.style.display = 'none';
                    milestonePopup.removeEventListener('transitionend', handler);
                }, { once: true }); // Use { once: true } for cleaner event removal
            }
        });
    }

    // Function to close the pop-up when clicking outside the pop-up content
    if (milestonePopup) {
        milestonePopup.addEventListener('click', (event) => {
            if (event.target === milestonePopup) {
                milestonePopup.classList.remove('active');
                milestonePopup.addEventListener('transitionend', function handler() {
                    milestonePopup.style.display = 'none';
                    milestonePopup.removeEventListener('transitionend', handler);
                }, { once: true }); // Use { once: true }
            }
        });
    }

    // Call initializeMilestonePopup after the DOM is ready and likely after sidebar has rendered
    // This is called directly on DOMContentLoaded in landing.html's inline script
    // or you can call it here if you ensure sidebar creation happens first.
    // For now, the inline script in landing.html handles calling this after renderSidebar().
});

// Make initializeMilestonePopup globally accessible if called from landing.html inline script
window.initializeMilestonePopup = initializeMilestonePopup;