// profile preview script
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('picture');
    const preview = document.getElementById('profile-preview');

    input.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '../uploads/default_profile.jpg'; 
        }
    });
});