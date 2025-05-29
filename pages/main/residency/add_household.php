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
    <title>UBISH Dashboard | Home</title>
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
                            Last Name: <input type="text" name="household_last_name">
                        </div>
                        
                        <label for="address"><b>Household Address</b></label>
                        <div>
                            House Number/Code: <input type="text" name="household_number">
                            Purok: <input type="text" name="household_purok">
                            Street: <input type="text" name="household_street">
                        </div>
                        <div>
                            District: <input type="text" name="household_district">
                            Barangay: <input type="text" name="household_barangay" value="Greenwater Village" readonly>
                        </div>

                        <label for="num_members"><b>Number of Family Members</b></label>
                        <input type="number" id="num_members" min="1" max="20" style="width:60px;" value="1">
                        <button type="button" name="generate_rows" id="generate-members-btn">Generate</button>

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
                        <!--
                        <div class="family-member">
                            <div class="family-member-actions">
                                <button type="button" class="add-member-btn">Add Member</button>
                                <button type="button" class="remove-member-btn">Remove Member</button>
                            </div>
                            <div class="family-member-table">
                                <p>Note: Please fill out the form below with the details of each household member.</p>
                                <table border="1" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Household Member(s)</th>
                                            <th rowspan="2">Relation to Head</th>
                                            <th rowspan="2">Sex</th>
                                            <th rowspan="2">Date of Birth</th>
                                            <th rowspan="2">Age</th>
                                        </tr>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="fname[]"></td>
                                            <td><input type="text" name="lname[]"></td>
                                            <td><input type="text" name="relation[]"></td>
                                            <td>
                                                <select name="sex[]">
                                                    <option value="" disabled selected>-- SELECT SEX</option>
                                                    <option value="M">Male</option>
                                                    <option value="F">Female</option>
                                                </select>
                                            </td>
                                            <td><input type="date" name="birthdate[]" id="dobInput"></td>
                                            <td>
                                                <span id="ageDisplay"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Civil Status</th>
                                            <th rowspan="2">Religion</th>
                                            <th rowspan="2">Schooling</th>
                                            <th rowspan="2">Highest Educational Attainment</th>
                                            <th rowspan="2">Present Job/Occupation</th>
                                            <th rowspan="2">Livelihood Training</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                    <select name="civilstatus[]">
                                                        <option value="" disabled selected>-- SELECT CIVIL STATUS</option>
                                                        <option value="Single">Single</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Widowed/r">Widowed/r</option>
                                                        <option value="Separated">Separated</option>
                                                    </select>
                                                </td>
                                            <td>
                                                <select name="religion[]">
                                                    <option value="" disabled selected>-- SELECT RELIGION</option>
                                                    <option value="Roman Catholic">Roman Catholic</option>
                                                    <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                                                    <option value="Islam">Islam</option>
                                                    <option value="Seventh Day Adventist">Seventh Day Adventist</option>
                                                    <option value="Methodist">Methodist</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="schooling[]">
                                                    <option value="" disabled selected>-- SELECT SCHOOLING</option>
                                                    <option value="In school">In school</option>
                                                    <option value="Out of school">Out of school</option>
                                                    <option value="Not yet in school">Not yet in school</option>
                                                    <option value="Graduate">Graduate</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="attainment[]">
                                                    <option value="" disabled selected>-- SELECT ATTAINMENT</option>
                                                    <option value="Elementary">Elementary</option>
                                                    <option value="High School">High School</option>
                                                    <option value="College">College</option>
                                                    <option value="Post-Graduate">Post-Graduate</option>
                                                    <option value="Vocational">Vocational</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="occupation[]"></td>
                                            <td><input type="text" name="livelihood_training[]"></td>
                                        </tr>
                                    </tbody>
                                    <thead>
                                        <tr>
                                            <th colspan="2">Employment</th>
                                            <th colspan="2">Est. Monthly Income</th>
                                            <th colspan="2" rowspan="2">
                                                <p style="cursor: help;" title="Indicate if PWD, OFW, Solo Parent, and/or IP.">
                                                    Others
                                                </p>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <th>Category</th>
                                            <th>Cash</th>
                                            <th>Kind</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td>
                                            <select name="emp_status[]">
                                                <option value="" disabled selected>-- SELECT EMPLOYMENT STATUS</option>
                                                <option value="Permanent">Permanent</option>
                                                <option value="Temporary">Temporary</option>
                                                <option value="Contractual">Contractual</option>
                                                <option value="Self-Employed">Self-Employed</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="emp_category[]">
                                                <option value="" disabled selected>-- SELECT EMPLOYMENT CATEGORY</option>
                                                <option value="Private">Private</option>
                                                <option value="Government">Government</option>
                                                <option value="Self-Employed">Self-Employed</option>
                                            </select>
                                        </td>
                                        <td>₱&nbsp;<input type="number" min="0" name="income_cash[]"></td>
                                        <td><input type="text" name="income_type[]"></td>
                                        <td colspan="2">
                                            <div class="indicate-others" style="display: flex;">
                                                <input type="checkbox" name="is_PWD[]" value="PWD">&nbsp;PWD&nbsp;
                                                <input type="checkbox" name="is_OFW[]" value="OFW">&nbsp;OFW&nbsp;
                                                <input type="checkbox" name="is_solo_parent[]" value="Solo Parent">&nbsp;Solo&nbsp;Parent&nbsp;
                                                <input type="checkbox" name="is_IP[]" value="IP">&nbsp;IP&nbsp;
                                            </div>
                                        </td>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        -->
                        <script>
                            document.querySelector('form[action="confirm_household.php"]').addEventListener('submit', function(e) {
                                const members = document.querySelectorAll('.family-member-table');
                                let hasError = false;
                                let errorMsg = '';
                                
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
                        <button type="submit" name="save-household">Save Household</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>