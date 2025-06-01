<?php
    include '../../../config/dbfetch.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <script src="js/sweetalert2.js"></script>
    <title>UBISH Dashboard | Add Household</title>
</head>
<body>
    <style>
        header {
            background-color: #e1f3e2 !important;
            border-bottom: 5px solid #356859 !important;
        }
        .logout {
            background-color: #e1f3e2 !important;
            color: #356859 !important;
            font-weight: bold !important;
            font-size: 1.1rem !important;
        }
        footer {
            background-color: #d0e9d2 !important;
            text-align: center !important;
            padding: 20px !important;
            color: #2b3d2f !important;
            border-top: 5px solid #356859 !important;
            margin-top: 60px !important;
        }
    </style>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="../../../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
                <h1>UBISH</h1>
            </div>
            <form method="POST">
                <nav>
                    <ul>
                        <li>
                            <button class="logout" style="cursor: pointer;" name="logout">Log Out</button>
                        </li>
                    </ul>
                </nav>
            </form>
        </div>
        <hr>
    </header>
    <main>
        <div class="dashboard-main">
            <div class="dashboard-sidebar">
                <ul>
                    <h3>Home</h3>
                    <li><a href="../../main/dashboard.php">Home</a></li>
                    <li><a href="../../main/account.php">Account</a></li>
                    
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../../main/documents.php">Documents</a></li>
                    <li><a href="../../main/announcements.php">Post Announcement</a></li>
                    
                    <h3>Tables & Requests</h3>
                    <li><a href="../../main/employee_table.php">Employee Table</a></li>

                    <!-- STANDARD -->
                    <?php if ($accessLevel >= 2) { echo '<li class="active"><a href="../../main/residency_management.php">Residency Management</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../../main/certificates.php">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../../main/permits.php">Permit Requests</a></li>'; } ?>
                    <!-- STANDARD -->
                    
                    <!-- FULL -->
                    <?php if ($accessLevel >= 3) { echo '<li><a href="../../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <!-- FULL -->
                    
                    <h3>Reports</h3>
                    <!-- STANDARD -->
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../../main/incidents.php">Incident Reports</a></li>'; }  ?>
                    <!-- STANDARD -->
                    
                    <li><a href="../main/incident_table.php">Incident History</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <div class="family-content">
                    <center>
                        <h1>Add Household</h1>
                        <h3>Household Information</h3>
                    </center>
                    <!-- Guiding Information -->
                    <div style="
                        background: #e6f7e6; 
                        border: 1px solid #356859; 
                        border-radius: 6px; 
                        padding: 16px; 
                        margin-bottom: 20px; 
                        color: #2b3d2f; 
                        width: 1024px; 
                        min-width: 0; 
                        display: block; 
                        box-sizing: 
                        border-box; 
                        margin: 8px auto;"
                    >
                        <strong>Instructions:</strong>
                        <ul style="margin: 8px 0 0 20px; text-align: left;">
                            <li>Fill in the <b>Household Head</b> and <b>Household Address</b> fields.</li>
                            <li>Enter the <b>Number of Family Members</b> and click "<b>Generate</b>" to create the member forms.</li>
                            <li>For each member, complete all required details.</li>
                            <li>After filling out all members, click "<b>Save Household</b>" to review your entries.</li>
                            <li>On the confirmation page, you can either "<b>Confirm</b>" to finalize or "<b>Go Back</b> to make changes.</li>
                        </ul>
                    </div>
                    <form method="POST" action="confirm_household.php">
                        <label for="head"><b>Household Head/Respondent</b></label>
                        <div>
                            First Name: <input type="text" name="household_first_name">
                            Middle Initial: <input type="text" name="household_middle_initial" maxlength="5">
                            Last Name: <input type="text" name="household_last_name">
                            Suffix: <input type="text" name="household_suffix" maxlength="5">
                        </div>
                        <br>
                        <label for="address"><b>Household Address</b></label>
                        <div>
                            House Number/Code: <input type="text" name="household_number">
                            Purok: <input type="text" name="household_purok">
                            Street: <input type="text" name="household_street">
                        </div>
                        <br>
                        <div>
                            District: <input type="text" name="household_district">
                            Barangay: <input type="text" name="household_barangay" value="Greenwater Village" readonly>
                        </div>
                        <br>
                        <label for="num_members"><b>Number of Family Members</b></label>
                        <input type="number" id="num_members" min="1" max="20" style="width:60px;" value="1">
                        <br>
                        <button type="button" name="generate_rows" id="generate-members-btn" class="custom-cancel-button">Generate</button>
                        <br>

                        <script>
                            document.getElementById('generate-members-btn').addEventListener('click', function(e) {
                                e.preventDefault();
                                var num = document.getElementById('num_members').value;
                                if (!num || num < 1) num = 1; // fallback
                                var xhr = new XMLHttpRequest();
                                xhr.open('POST', 'generate_members.php', true);
                                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                xhr.onload = function() {
                                    if (xhr.status === 200) {
                                        document.getElementById('family-members-container').innerHTML = xhr.responseText;
                                    }
                                };
                                xhr.send('num_members=' + encodeURIComponent(num));
                            });
                        </script>
                        <div id="family-members-container"></div>

                        <script>
                            document.querySelector('form[action="confirm_household.php"]').addEventListener('submit', function(e) {
                                const householdFirstName = document.querySelector('input[name="household_first_name"]')?.value.trim();
                                const householdLastName = document.querySelector('input[name="household_last_name"]')?.value.trim();
                                const householdNumber = document.querySelector('input[name="household_number"]')?.value.trim();
                                const householdBarangay = document.querySelector('input[name="household_barangay"]')?.value.trim();
                                let hasError = false;
                                let errorMsg = '';

                                if (!householdFirstName || !householdLastName || !householdNumber || !householdBarangay) {
                                    hasError = true;
                                    errorMsg += 'Please fill out all required household information:<br>';
                                    if (!householdFirstName) errorMsg += 'Household Head First Name<br>';
                                    if (!householdLastName) errorMsg += 'Household Head Last Name<br>';
                                    if (!householdNumber) errorMsg += 'House Number/Code<br>';
                                    if (!householdBarangay) errorMsg += 'Barangay<br>';
                                }
                                
                                const members = document.querySelectorAll('.family-member-table');
                                if (members.length === 0) {
                                    e.preventDefault();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'No Family Members',
                                        text: 'Please generate and fill out at least one family member before saving the household.',
                                        confirmButtonText: 'OK'
                                    });
                                    return;
                                }
                                
                                members.forEach((member, idx) => {
                                    const fname = member.querySelector('input[name="fname[]"]')?.value.trim();
                                    const lname = member.querySelector('input[name="lname[]"]')?.value.trim();
                                    const sex = member.querySelector('select[name="sex[]"]')?.value;
                                    const birthdate = member.querySelector('input[name="birthdate[]"]')?.value;
                                    const civil = member.querySelector('select[name="civilstatus[]"]')?.value;
                                    const schooling = member.querySelector('select[name="schooling[]"]')?.value;
                                    const emp = member.querySelector('select[name="emp_status[]"]')?.value;

                                    if (!fname || !lname || !sex || !birthdate || !civil || !schooling || !emp) {
                                        hasError = true;
                                        errorMsg = `Please fill out all required fields for Family Member ${idx+1}:<br>
                                            ${!fname ? 'First Name<br>' : ''}
                                            ${!lname ? 'Last Name<br>' : ''}
                                            ${!sex ? 'Sex<br>' : ''}
                                            ${!birthdate ? 'Birthdate<br>' : ''}
                                            ${!civil ? 'Civil Status<br>' : ''}
                                            ${!schooling ? 'Schooling<br>' : ''}
                                            ${!emp ? 'Employment Status<br>' : ''}`;
                                        return false;
                                    }

                                    if (birthdate && new Date(birthdate) > new Date()) {
                                        hasError = true;
                                        errorMsg = `Birthdate for Family Member ${idx+1} cannot be in the future.`;
                                        return false;
                                    }
                                });

                                if (hasError) {
                                    e.preventDefault();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Validation Error',
                                        html: errorMsg,
                                        confirmButtonText: 'OK'
                                    });
                                }
                            });
                        </script>
                        <button type="submit" name="save-household" class="custom-cancel-button">Save Household</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
    <script>
        function calculateAge(birthdate) {
            if (!birthdate) return '';
            var today = new Date();
            var bdate = new Date(birthdate);
            var age = today.getFullYear() - bdate.getFullYear();
            var m = today.getMonth() - bdate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < bdate.getDate())) {
                age--;
            }
            return (isNaN(age) || age < 0) ? '' : age + " year(s) old";
        }

        function attachAgeListeners() {
            document.querySelectorAll('.birthdate-input').forEach(function(input) {
                var tr = input.closest('tr');
                var ageSpan = tr ? tr.querySelector('.age-display') : null;
                if (ageSpan) {
                    ageSpan.textContent = calculateAge(input.value);

                    input.addEventListener('input', function() {
                        ageSpan.textContent = calculateAge(this.value);
                    });
                }
            });
        }

        attachAgeListeners();

        document.getElementById('generate-members-btn').addEventListener('click', function() {
            setTimeout(attachAgeListeners, 100);
        });
        </script>
</body>
</html>