/* checkboxes for registration requests */
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

/* checkboxes for profile update requests */
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

/* checkboxes for multiple employee deletion */
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.deletion-checkbox');
    const deleteButton = document.getElementById('deleteSelectedEmp');

    // check if any checkbox is selected
    function toggleDeleteButton() {
        const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        deleteButton.disabled = !anyChecked;
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleDeleteButton);
    });

    toggleDeleteButton();
});