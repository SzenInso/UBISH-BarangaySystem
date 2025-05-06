document.addEventListener("DOMContentLoaded", function () {
    const sortDropdown = document.getElementById("sort");
    const filterSexDropdown = document.getElementById("filter-sex");
    const filterAccessLvlDropdown = document.getElementById("filter-access-level");

    function fetchFilteredData() {
        const sort = sortDropdown.value;
        const filterSex = filterSexDropdown.value;
        const filterAccessLvl = filterAccessLvlDropdown ? filterAccessLvlDropdown.value : ""; // handle missing dropdown
        
        // AJAX to dynamically display sort and filters
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.open("POST", "../main/employee_table.php", true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                document.querySelector("#employee-table tbody").innerHTML = xmlhttp.responseText;
            }
        };

        const params = `action=fetch&sort=${sort}&filterSex=${filterSex}&filterAccessLvl=${filterAccessLvl}`;
        xmlhttp.send(params);
    }

    function resetFilters() {
        // reset dropdowns to default values
        sortDropdown.value = "";
        filterSexDropdown.value = "";
        filterAccessLvlDropdown.value = "";

        // fetch default data
        fetchFilteredData();
    }

    // add event listeners to dropdowns
    sortDropdown.addEventListener("change", fetchFilteredData);
    filterSexDropdown.addEventListener("change", fetchFilteredData);
    filterAccessLvlDropdown.addEventListener("change", fetchFilteredData);
    resetButton.addEventListener("click", resetFilters);
});