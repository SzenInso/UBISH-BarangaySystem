document.addEventListener('DOMContentLoaded', function () {
    const dobInput = document.getElementById('dobInput');
    const ageDisplay = document.getElementById('ageDisplay');
    function updateAge() {
        const dobValue = dobInput.value;

        if (dobValue) {
            const dob = new Date(dobValue);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            const dayDiff = today.getDate() - dob.getDate();

            if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                age--;
            }

            ageDisplay.textContent = age;
        } else {
            ageDisplay.textContent = 'N/A';
        }
    }

    dobInput.addEventListener('change', updateAge);
    updateAge();
});