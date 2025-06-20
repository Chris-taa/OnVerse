document.addEventListener('DOMContentLoaded', () => {
    const openPopupBtn = document.getElementById('openPopupBtn');
    const milestonePopup = document.getElementById('milestonePopup');
    const closeButton = document.querySelector('.close-button');

    // Function to open the pop-up
    if (openPopupBtn) {
        openPopupBtn.addEventListener('click', () => {
            milestonePopup.style.display = 'flex'; // Show the pop-up
        });
    }

    // Function to close the pop-up when the close button is clicked
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            milestonePopup.style.display = 'none'; // Hide the pop-up
        });
    }

    // Function to close the pop-up when clicking outside the pop-up content
    if (milestonePopup) {
        milestonePopup.addEventListener('click', (event) => {
            if (event.target === milestonePopup) {
                milestonePopup.style.display = 'none';
            }
        });
    }
});