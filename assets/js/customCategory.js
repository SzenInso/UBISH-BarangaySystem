document.addEventListener("DOMContentLoaded", 
    function () {
        const categoryOthers = document.getElementById("categoryOthers");
        const customCategoryInput = document.getElementById("customCategory");
        const categoryRadios = document.querySelectorAll('input[name="category"]');

        categoryRadios.forEach(radio => {
            radio.addEventListener("change", 
                    function () {
                        if (categoryOthers.checked) {
                            customCategoryInput.style.display = "block"; 
                            customCategoryInput.required = true; 
                        } else {
                            customCategoryInput.style.display = "none"; 
                            customCategoryInput.required = false;
                            customCategoryInput.value = ""; 
                        }
                    }
                );
            }
        );
    }
);