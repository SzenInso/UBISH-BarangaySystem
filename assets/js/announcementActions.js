document.addEventListener("DOMContentLoaded", function () {
    // when kebab menu button is clicked
    document.querySelectorAll(".kebab-btn").forEach(button => {
        button.addEventListener("click", function (e) {
            const menu = this.nextElementSibling; // gets next sibline element (kebab-menu)
            const isVisible = menu.style.display === "block"; // checks if actions menu is visible
            
            // toggles menu visibility
            document.querySelectorAll(".kebab-menu").forEach(m => m.style.display = "none");
            menu.style.display = isVisible ? "none" : "block";
            e.stopPropagation();
        });
    });

    document.addEventListener("click", function () {
        // close menu if clicked outside
        document.querySelectorAll(".kebab-menu").forEach(menu => menu.style.display = "none");
    });
});