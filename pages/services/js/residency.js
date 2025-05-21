document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('residency-form');
  if (!form) return;

  const modalStep1 = document.getElementById('modal-step1');
  const modalStep2 = document.getElementById('modal-step2');
  const closeStep1Btn = document.getElementById('close-step1');
  const closeStep2Btn = document.getElementById('close-step2');
  const reviewInfo = document.getElementById('review-info');
  const backEditBtn = document.getElementById('back-edit');
  const finalSubmitBtn = document.getElementById('final-submit');
  const openRequestBtn = document.getElementById('openRequestBtn');

  const residencyDurationInput = form.querySelector('input[name="years_residency"]');
  const residencyDurationUnitSelect = form.querySelector('select[name="duration_unit"]');

  if (!residencyDurationInput || !residencyDurationUnitSelect) return;

  modalStep1.style.display = 'none';
  modalStep2.style.display = 'none';

  function openModal(modal) {
    modal.style.display = 'flex';
    setTimeout(() => {
      modal.classList.add('show');
      modal.setAttribute('aria-hidden', 'false');
      modal.focus();
    }, 10);
  }

  function closeModal(modal) {
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    setTimeout(() => {
      modal.style.display = 'none';
    }, 300);
  }

  function updateMinResidency() {
    const unit = residencyDurationUnitSelect.value;
    const min = unit === 'months' ? 6 : 1;
    residencyDurationInput.min = min;
    if (residencyDurationInput.value !== '') {
      if (parseInt(residencyDurationInput.value) < min) {
        residencyDurationInput.value = min;
      }
    }
  }

  residencyDurationUnitSelect.addEventListener('change', updateMinResidency);
  updateMinResidency();

  if (openRequestBtn) {
    openRequestBtn.addEventListener('click', () => openModal(modalStep1));
  }

  if (closeStep1Btn) {
    closeStep1Btn.addEventListener('click', () => closeModal(modalStep1));
  }

  if (closeStep2Btn) {
    closeStep2Btn.addEventListener('click', () => closeModal(modalStep2));
  }

  function handleFormSubmit(e) {
    e.preventDefault();

    const unit = residencyDurationUnitSelect.value;
    const value = parseInt(residencyDurationInput.value);
    const min = unit === 'months' ? 6 : 1;

    if (value < min) {
      alert(`Minimum residency duration is ${min} ${unit}.`);
      residencyDurationInput.focus();
      return;
    }

    const firstname = form.firstname.value.trim();
    const middleInitial = form.middle_initial.value.trim();
    const lastname = form.lastname.value.trim();
    const suffix = form.suffix.value.trim();
    const age = form.age.value.trim();
    const contact = form.contactNumber.value.trim();
    const street = form.street.value.trim();
    const barangay = form.barangay.value.trim();
    const gender = form.gender.value;
    const yearsResidency = residencyDurationInput.value.trim();
    const durationUnit = residencyDurationUnitSelect.value;
    const purpose = form.purpose.value.trim();

    const fullName = `${firstname} ${middleInitial ? middleInitial + '.' : ''} ${lastname}${suffix ? ', ' + suffix : ''}`;
    const residencyDisplay = `${yearsResidency} ${durationUnit.charAt(0).toUpperCase() + durationUnit.slice(1)}`;

    const reviewHTML = `
      <p><strong>Full Name:</strong> ${fullName}</p>
      <p><strong>Age:</strong> ${age}</p>
      <p><strong>Contact Number:</strong> ${contact}</p>
      <p><strong>Address:</strong> ${street}, ${barangay}</p>
      <p><strong>Gender:</strong> ${gender}</p>
      <p><strong>Residency Duration:</strong> ${residencyDisplay}</p>
      <p><strong>Purpose:</strong> ${purpose}</p>
    `;

    reviewInfo.innerHTML = reviewHTML;

    closeModal(modalStep1);
    openModal(modalStep2);
  }

  form.addEventListener('submit', handleFormSubmit);

  if (backEditBtn) {
    backEditBtn.addEventListener('click', () => {
      closeModal(modalStep2);
      openModal(modalStep1);
    });
  }

  if (finalSubmitBtn) {
  finalSubmitBtn.addEventListener('click', () => {
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    Swal.fire({
      icon: 'success',
      title: 'Submitted!',
      text: 'Your certificate request has been submitted successfully!',
      confirmButtonText: 'OK'
    }).then(() => {
      closeModal(modalStep2);
      form.submit(); 
    });
  });
}


  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (modalStep1.classList.contains('show')) closeModal(modalStep1);
      if (modalStep2.classList.contains('show')) closeModal(modalStep2);
    }
  });
});
