// residency_modal.js
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('viewModal');
  const closeBtn = document.getElementById('modalCloseBtn');
  const form = document.getElementById('editRequestForm');

  // Function to open modal and fill fields with data
  window.openResidencyModal = function(data) {
    modal.style.display = 'flex';

    document.getElementById('modal_request_id').value = data.id || '';
    document.getElementById('modal_firstname').value = data.firstname || '';
    document.getElementById('modal_middle_initial').value = data.middle_initial || '';
    document.getElementById('modal_lastname').value = data.lastname || '';
    document.getElementById('modal_suffix').value = data.suffix || '';
    document.getElementById('modal_age').value = data.age || '';
    document.getElementById('modal_street').value = data.street || '';
    document.getElementById('modal_barangay').value = data.barangay || '';
    document.getElementById('modal_gender').value = data.gender || '';
    document.getElementById('modal_years_residency').value = data.years_residency || '';
    document.getElementById('modal_purpose').value = data.purpose || '';
  };

  closeBtn.onclick = function() {
    modal.style.display = 'none';
  };

  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = 'none';
    }
  };

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(form);
    fetch('handle_cert_actions.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      alert(data.message);
      if (data.success) {
        modal.style.display = 'none';
        location.reload();
      }
    })
    .catch(err => {
      alert('Error processing request.');
      console.error(err);
    });
  });
});
