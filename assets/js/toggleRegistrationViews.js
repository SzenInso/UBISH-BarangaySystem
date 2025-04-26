function updateRegistrationViewAllButton() {
    const allViews = document.querySelectorAll('.registration-view');
    const viewAllButton = document.getElementById('viewAllBtn');
    let allOpen = true;

    allViews.forEach(function(view) {
        if (!view.classList.contains('active')) {
            allOpen = false;
        }
    });

    if (allOpen) {
        viewAllButton.textContent = "Collapse All";
    } else {
        viewAllButton.textContent = "View All";
    }
}

function toggleRegistrationView(id, button) {
    const element = document.getElementById(id);
    element.classList.toggle("active");

    if (element.classList.contains("active")) {
        button.textContent = "Collapse";
    } else {
        button.textContent = "View More";
    }

    updateRegistrationViewAllButton();
}

function toggleRegistrationViewAll(button) {
    const allViews = document.querySelectorAll('.registration-view');
    const allButtons = document.querySelectorAll('.view-btn');
    let allOpen = true;

    allViews.forEach(function(view) {
        if (!view.classList.contains('active')) {
            allOpen = false;
        }
    });

    if (allOpen) {
        allViews.forEach(function(view) {
            view.classList.remove('active');
        });
        allButtons.forEach(function(btn) {
            btn.textContent = "View More";
        });
        button.textContent = "View All";
    } else {
        allViews.forEach(function(view) {
            view.classList.add('active');
        });
        allButtons.forEach(function(btn) {
            btn.textContent = "Collapse";
        });
        button.textContent = "Collapse All";
    }
}