// profile preview script
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('picture');
    const preview = document.getElementById('profile-preview');

    input.addEventListener('change', function (event) {
        const file = event.target.files[0]; // first image file selected from the input
        if (file) { // if image is selected
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result; // dynamically display selected image
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '../../uploads/default_profile.jpg'; // else, display default image
        }
    });
});