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
      const address = `${req.street}, ${req.barangay}`;
      const pdfUrl = `residency/generate_certificate.php?id=${req.id}`;

      // Show only one value depending on which field is filled
      const residencyDuration = req.years_residency 
        ? `${req.years_residency} year(s)` 
        : (req.months_residency 
            ? `${req.months_residency} month(s)` 
            : 'N/A');

      return `
        <tr>
          <td>${fullName}</td>
          <td>${req.age}</td>
          <td>${req.contactNumber}</td>
          <td>${address}</td>
          <td>${req.gender}</td>
          <td>${residencyDuration}</td>
          <td>${req.purpose}</td>
          <td>
            <button 
              class="btn-action btn-edit" 
              data-id="${req.id}" 
              data-purpose="${req.purpose}" 
              data-years="${req.years_residency || ''}" 
              data-months="${req.months_residency || ''}">
              Edit
            </button>
            <button 
              class="btn-action btn-delete" 
              data-id="${req.id}">
              Delete
            </button>
            <a 
              href="${pdfUrl}" 
              target="_blank" 
              class="btn-action btn-pdf">
              Generate PDF
            </a>
          </td>
        </tr>
      `;
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
              // After rejection, delete the record from DB
              Swal.fire({
                title: 'Request Rejected',
                html: data.message + '. This request will be deleted from the list and in the <strong>DATABASE</strong>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Keep it'
              }).then(async (result) => {
                const fetchOptions = {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({
                    id: data.id,
                    action: result.isConfirmed ? 'delete' : 'none'
                  })
                };
                if (result.isConfirmed) {
                  const delRes = await fetch('residency/handle_cert_actions.php', fetchOptions);
                  const delData = await delRes.json();

                  if (delData.success) {
                    Swal.fire('Deleted!', delData.message, 'success');
                  } else {
                    Swal.fire('Error!', delData.message, 'error');
                  }
                } else {
                  Swal.fire('Rejected!', 'The request has been marked as rejected.', 'info');
                }
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

  //Edit the residency contents to fit the certificate.
    const editModal = document.getElementById('editModal');
    const closeModalBtn = editModal.querySelector('.close');
    const editForm = document.getElementById('editForm');
    const editYearsInput = document.getElementById('editYears');
    const editMonthsInput = document.getElementById('editMonths');

    editYearsInput.addEventListener('input', () => {
      if (editYearsInput.value > 0) {
        editMonthsInput.disabled = true;
      } else {
        editMonthsInput.disabled = false;
      }
    });

    editMonthsInput.addEventListener('input', () => {
      if (editMonthsInput.value > 0) {
        editYearsInput.disabled = true;
      } else {
        editYearsInput.disabled = false;
      }
    });

    document.addEventListener('click', function (e) {
      const target = e.target;

      // 🟡 Open Edit Modal
      if (target.classList.contains('btn-edit')) {
        document.getElementById('editId').value = target.getAttribute('data-id');
        document.getElementById('editPurpose').value = target.getAttribute('data-purpose');
        document.getElementById('editYears').value = target.getAttribute('data-years') || 0;
        document.getElementById('editMonths').value = target.getAttribute('data-months') || 0;
        editModal.style.display = 'block';
      }
    });

    // 🔴 Close Modal
    closeModalBtn.onclick = () => { editModal.style.display = 'none'; };
    window.onclick = (e) => { if (e.target == editModal) editModal.style.display = 'none'; };

    // ✅ Handle Edit Form Submission
    editForm.addEventListener('submit', async function (e) {
      e.preventDefault();

      const id = document.getElementById('editId').value;
      const purpose = document.getElementById('editPurpose').value.trim();
      const yearsInput = parseInt(document.getElementById('editYears').value);
      const monthsInput = parseInt(document.getElementById('editMonths').value);

      // 🟡 Default both to null
      let years = null;
      let months = null;

      // 🟢 Priority: if months is entered, use it; otherwise use years
      if (!isNaN(monthsInput) && monthsInput > 0) {
        months = monthsInput;
      } else if (!isNaN(yearsInput) && yearsInput > 0) {
        years = yearsInput;
      }

      const res = await fetch('residency/update_request.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          id,
          action: 'edit',
          purpose,
          years,
          months
        })
      });

      const result = await res.json();
      if (result.success) {
        Swal.fire('Updated!', result.message, 'success');
        editModal.style.display = 'none';
        loadTables(searchInput.value);
      } else {
        Swal.fire('Error!', result.message, 'error');
      }
    });

  const target = e.target;
  // 🟠 DELETE approved requests
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
});

  // Search input event for filtering pending requests
  searchInput.addEventListener('input', (e) => {
      const term = e.target.value.trim();
      loadTables(term);
    });
    loadTables();
  });
