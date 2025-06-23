document.addEventListener('DOMContentLoaded', function() {
  // Edit Work Modal Functionality
  const editButtons = document.querySelectorAll('.edit-work-btn');
  const modal = document.getElementById('editWorkModal');
  const closeButtons = document.querySelectorAll('.close-modal');
  
  // Open modal when edit button is clicked
  editButtons.forEach(button => {
    button.addEventListener('click', function() {
      const workId = this.getAttribute('data-work-id');
      const workTitle = this.getAttribute('data-work-title');
      const workStatus = this.getAttribute('data-work-status');
      
      document.getElementById('edit_work_id').value = workId;
      document.getElementById('edit_work_title').value = workTitle;
      document.getElementById('edit_work_status').value = workStatus;
      
      modal.style.display = 'block';
    });
  });
  
  // Close modal when X or cancel button is clicked
  closeButtons.forEach(button => {
    button.addEventListener('click', function() {
      modal.style.display = 'none';
    });
  });
  
  // Close modal when clicking outside the modal
  window.addEventListener('click', function(event) {
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  });
  
  // Handle form submission
  document.getElementById('editWorkForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('update_work.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Karya berhasil diperbarui!');
        modal.style.display = 'none';
        location.reload(); // Refresh the page to show changes
      } else {
        alert('Gagal memperbarui karya: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Terjadi kesalahan saat memperbarui karya.');
    });
  });
});