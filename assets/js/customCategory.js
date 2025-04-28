// custom category script - displays input field with "Others" option in category is selected
document.addEventListener("DOMContentLoaded", 
    function () {
        const categoryOthers = document.getElementById("categoryOthers");
        const customCategoryInput = document.getElementById("customCategory");
        const categoryRadios = document.querySelectorAll('input[name="category"]');

        categoryRadios.forEach(radio => {
            radio.addEventListener("change", 
                    function () {
                        if (categoryOthers.checked) { // if selected
                            customCategoryInput.style.display = "block"; // display: block applied in input field beside "Others" option
                            customCategoryInput.required = true; 
                        } else { // else if not selected, or option is changed
                            customCategoryInput.style.display = "none"; // display: none applied in input field
                            customCategoryInput.required = false;
                            customCategoryInput.value = ""; 
                        }
                    }
                );
            }
        );
    }
);