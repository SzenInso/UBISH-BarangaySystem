document.addEventListener('DOMContentLoaded', () => {
  const pendingTbody = document.getElementById('residency-requests-tbody');
  const approvedTbody = document.getElementById('approved-requests-tbody');
  const searchInput = document.getElementById('search-input');

  function loadTables(searchTerm = '') {
    fetch('fetch_residency_requests.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({search: searchTerm})
    })
      .then(res => res.json())
      .then(data => {
        renderPending(data.pending);
        renderApproved(data.approved);
      })
      .catch(() => {
        pendingTbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:red;">Failed to load data.</td></tr>`;
        approvedTbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:red;">Failed to load data.</td></tr>`;
      });
  }

  function renderPending(requests) {
    if (requests.length === 0) {
      pendingTbody.innerHTML = `<tr><td colspan="7" style="text-align:center;">No pending requests.</td></tr>`;
      return;
    }

    pendingTbody.innerHTML = requests.map(req => {
      const fullName = `${req.firstname} ${req.middle_initial ? req.middle_initial + '.' : ''} ${req.lastname} ${req.suffix || ''}`.trim();
      const yearsResidency = req.years_residency ? req.years_residency + ' year(s)' : (req.months_residency ? req.months_residency + ' month(s)' : 'N/A');
      const address = `${req.street}, ${req.barangay}`;
      return `
        <tr>
          <td>${fullName}</td>
          <td>${req.age}</td>
          <td>${address}</td>
          <td>${req.gender}</td>
          <td>${yearsResidency}</td>
          <td>${req.purpose}</td>
          <td>
            <button 
                class="btn-approve" 
                data-id="${req.id}" 
                style="background-color: #4CAF50; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-right: 5px; font-weight: bold;">
                Approve
            </button>
            <button 
                class="btn-reject" 
                data-id="${req.id}" 
                style="background-color: #f44336; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                Reject
            </button>

          </td>
        </tr>`;
    }).join('');
  }

  // Render approved requests
  function renderApproved(requests) {
    if (requests.length === 0) {
      approvedTbody.innerHTML = `<tr><td colspan="7" style="text-align:center;">No approved requests.</td></tr>`;
      return;
    }

    approvedTbody.innerHTML = requests.map(req => {
      const fullName = `${req.firstname} ${req.middle_initial ? req.middle_initial + '.' : ''} ${req.lastname} ${req.suffix || ''}`.trim();
      const yearsResidency = req.years_residency ? req.years_residency + ' year(s)' : (req.months_residency ? req.months_residency + ' month(s)' : 'N/A');
      const address = `${req.street}, ${req.barangay}`;
      // Assuming you have a PDF generator page with id param
      const pdfUrl = `generate_certificate.php?id=${req.id}`;
      return `
        <tr>
          <td>${fullName}</td>
          <td>${req.age}</td>
          <td>${address}</td>
          <td>${req.gender}</td>
          <td>${yearsResidency}</td>
          <td>${req.purpose}</td>
          <td>
            <a 
                href="${pdfUrl}" 
                target="_blank" 
                style="background-color: #007BFF; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block;">
                Generate PDF
            </a>
            </td>

        </tr>`;
    }).join('');
  }

  // Handle approve/reject button clicks
  pendingTbody.addEventListener('click', (e) => {
  if (e.target.classList.contains('btn-approve') || e.target.classList.contains('btn-reject')) {
    const id = e.target.dataset.id;
    const action = e.target.classList.contains('btn-approve') ? 'approve' : 'reject';
    const actionText = action.charAt(0).toUpperCase() + action.slice(1); // "Approve" or "Reject"

    Swal.fire({
      title: `Are you sure you want to ${actionText.toLowerCase()} this request?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: `Yes, ${actionText}`,
      cancelButtonText: 'Cancel',
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('handle_cert_actions.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: `request_id=${encodeURIComponent(id)}&action=${encodeURIComponent(action)}`
        })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              Swal.fire('Success', data.message, 'success');
              loadTables(searchInput.value); 
            } else {
              Swal.fire('Error', data.message, 'error');
            }
          })
          .catch(() => {
            Swal.fire('Error', 'Failed to update the request.', 'error');
          });
      }
    });
  }
});


  // Search input event for filtering pending requests
  searchInput.addEventListener('input', (e) => {
    const term = e.target.value.trim();
    loadTables(term);
  });

  // Initial load without filter
  loadTables();
});
