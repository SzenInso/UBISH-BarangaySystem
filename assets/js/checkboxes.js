document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.selection-checkbox');
    const approveButton = document.getElementById('approveSelectedBtn');
    const denyButton = document.getElementById('denySelectedBtn');

    // check if any checkbox is selected
    function toggleButtons() {
        const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        approveButton.disabled = !anyChecked;
        denyButton.disabled = !anyChecked;
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleButtons);
    });

    toggleButtons();
});

document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.updates-checkbox');
    const approveButton = document.getElementById('approveUpdateSelectedBtn');
    const denyButton = document.getElementById('denyUpdateSelectedBtn');

    // check if any checkbox is selected
    function toggleButtons() {
        const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        approveButton.disabled = !anyChecked;
        denyButton.disabled = !anyChecked;
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleButtons);
    });

    toggleButtons();
});