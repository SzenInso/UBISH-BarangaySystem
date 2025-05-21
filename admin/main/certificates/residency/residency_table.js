document.addEventListener('DOMContentLoaded', () => {
  const pendingTbody = document.getElementById('residency-requests-tbody');
  const approvedTbody = document.getElementById('approved-requests-tbody');
  const searchInput = document.getElementById('search-input');

  function loadTables(searchTerm = '') {
    fetch('residency/fetch_residency_requests.php', {
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
          <td>${req.contactNumber}</td>
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
      const pdfUrl = `residency/generate_certificate.php?id=${req.id}`;
      return `
        <tr>
          <td>${fullName}</td>
          <td>${req.age}</td>
          <td>${req.contactNumber}</td>
          <td>${address}</td>
          <td>${req.gender}</td>
          <td>${yearsResidency}</td>
          <td>${req.purpose}</td>
          <td>
            <button 
              class="btn-delete" 
              data-id="${req.id}" 
              style="background-color: #DC3545; color: white; padding: 6px 10px; margin-right: 5px; border-radius: 4px; font-weight: bold;">
              Delete
            </button>
          </td>
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

 pendingTbody.addEventListener('click', (e) => {
  if (e.target.classList.contains('btn-approve') || e.target.classList.contains('btn-reject')) {
    const id = e.target.dataset.id;
    const action = e.target.classList.contains('btn-approve') ? 'approve' : 'reject';
    const actionText = action.charAt(0).toUpperCase() + action.slice(1);

    Swal.fire({
      title: `Are you sure you want to ${actionText.toLowerCase()} this request?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: `Yes, ${actionText}`,
      cancelButtonText: 'Cancel',
      confirmButtonColor: action === 'approve' ? '#4CAF50' : '#f44336',
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('residency/handle_cert_actions.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id, action: action })
        })
        .then(res => res.json())
        .then(data => {
          console.log('Response data:', data);
          if (data.success) {
            if (action === 'approve') {
              Swal.fire({
                title: `Message this number: <b>${data.contactNumber}</b>`,
                html: `
                  <strong>Please inform the requester to bring the following:</strong><br><br>
                  <ul style="text-align: left;">
                    <li><strong>Processing fee of less than 100</strong></li>
                    <li><strong>Cedula</strong></li>
                    <li><strong>Valid ID</strong></li>
                  </ul>
                  <small>The button will be enabled in <span id="countdown">3</span> seconds.</small>
                `,
                icon: 'info',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                  const confirmBtn = Swal.getConfirmButton();
                  confirmBtn.disabled = true;

                  let countdown = 5;
                  const countdownEl = document.getElementById('countdown');

                  const interval = setInterval(() => {
                    countdown--;
                    countdownEl.textContent = countdown;
                    if (countdown <= 0) {
                      clearInterval(interval);
                      confirmBtn.disabled = false;
                      countdownEl.textContent = '';
                    }
                  }, 1000);
                }
              }).then(() => {
                loadTables(searchInput.value);
              });
            } else {
              // Just reload for rejected
              Swal.fire('Success', data.message, 'success').then(() => {
                loadTables(searchInput.value);
              });
            }
          } else {
            Swal.fire('Error', data.message, 'error');
          }
        })
        .catch((error) => {
          console.error('Fetch error:', error);
          Swal.fire('Error', 'Failed to update the request.', 'error');
        });
      }
    });
  }
});


document.addEventListener('click', async function (e) {
  const target = e.target;

// 🟠 DELETE with SweetAlert
  if (target.classList.contains('btn-delete')) {
    const requestId = target.getAttribute('data-id');

    Swal.fire({
      title: 'Are you sure?',
      text: 'This will permanently delete the request.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
      if (result.isConfirmed) {
        const res = await fetch('residency/update_request.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: requestId, action: 'delete' })
        });
        const result = await res.json();
        if (result.success) {
          Swal.fire('Deleted!', result.message, 'success');
          loadTables(searchInput.value);
        } else {
          Swal.fire('Error!', result.message, 'error');
        }
      }
    });
  }

      // EDIT FUNCTIONALITY
      if (target.classList.contains('btn-edit')) {
        const requestId = target.getAttribute('data-id');
        const row = target.closest('tr');
        const cells = row.querySelectorAll('td');
        const editModal = new bootstrap.Modal(document.getElementById('editRequestModal'));
        editModal.show();

        currentEditId = requestId;

        document.getElementById('editRequestId').value = requestId;
        document.getElementById('editPurpose').value = cells[6].textContent.trim();
        const yearsText = cells[5].textContent.trim();
        document.getElementById('editYears').value = yearsText.includes('year') ? parseInt(yearsText) : '';
        document.getElementById('editMonths').value = yearsText.includes('month') ? parseInt(yearsText) : '';

      }
});

// ✅ Submit the Edit Modal
document.getElementById('editRequestForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const requestId = document.getElementById('editRequestId').value;
  const newPurpose = document.getElementById('editPurpose').value;
  const newYears = parseInt(document.getElementById('editYears').value) || 0;
  const newMonths = parseInt(document.getElementById('editMonths').value) || 0;

  const res = await fetch('residency/update_request.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      id: requestId,
      action: 'edit',
      purpose: newPurpose,
      years: newYears,
      months: newMonths
    })
  });

  const result = await res.json();
  const modal = bootstrap.Modal.getInstance(document.getElementById('editRequestModal'));

  if (result.success) {
    modal.hide();
    Swal.fire('Success!', result.message, 'success');
    loadTables(searchInput.value);
  } else {
    Swal.fire('Error!', result.message, 'error');
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
