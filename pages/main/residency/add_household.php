<?php
    include '../../../config/dbfetch.php';

    // access level verification
    if (!isset($_SESSION['user_id']) || $accessLevel < 2) {
        header("Location: ../dashboard.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="../css/dashPages.css">
    <link rel="icon" type="image/x-icon" href="../../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="js/sweetalert2.js"></script>
    <title>Greenwater Village Dashboard | Add Household</title>
</head>
<body>
    <div class="wrapper">
                <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <ul>
                <h2>
                    <div class="dashboard-greetings">
                        <?php 
                           $stmt = $pdo->prepare("SELECT * FROM employee_details WHERE emp_id = :emp_id");
                            $stmt->execute([":emp_id" => $_SESSION['emp_id']]);
                            $row = $stmt->fetch(PDO::FETCH_ASSOC); {
                        ?>
                        <?php
                            }
                        ?>
                        <center>
                        <div class="user-info d-flex align-items-center">
                            <img src="../<?php echo $row['picture']; ?>" 
                                class="avatar img-fluid rounded-circle me-2" 
                                alt="<?php echo $row['first_name']; ?>" 
                                width="70" height="70">
                        </div>
                            <span class="text-dark fw-semibold"><?php echo $row['first_name']; ?></span>
                        </center>
                    </div>
                </h2>

                <h3><i class="fas fa-home"></i> Home</h3>
                <li class="active"><a href="../main/dashboard.php"><i class="fas fa-tachometer-alt"></i> Home</a></li>
                <li><a href="../main/account.php"><i class="fas fa-user"></i> Account</a></li>

                <h3><i class="fas fa-folder-open"></i> Documents & Disclosure</h3>
                <li><a href="../main/documents.php"><i class="fas fa-file-alt"></i> Documents</a></li>
                <li><a href="../main/announcements.php"><i class="fas fa-bullhorn"></i> Post Announcement</a></li>

                <h3><i class="fas fa-table"></i> Tables & Requests</h3>
                <li><a href="../main/employee_table.php"><i class="fas fa-users"></i> Employee Table</a></li>

                <!-- STANDARD ACCESS LEVEL -->
                <?php if ($accessLevel >= 2): ?>
                    <li><a href="../main/residency_management.php"><i class="fas fa-house-user"></i> Residency Management</a></li>
                    <!-- <li><a href="../main/certificates.php"><i class="fas fa-certificate"></i> Certificate Requests</a></li> -->
                    <!-- <li><a href="../main/permits.php"><i class="fas fa-id-badge"></i> Permit Requests</a></li> -->
                <?php endif; ?>

                <!-- FULL ACCESS LEVEL -->
                <?php if ($accessLevel >= 3): ?>
                    <li><a href="../main/account_requests.php"><i class="fas fa-user-check"></i> Account Requests</a></li>
                <?php endif; ?>

                <h3><i class="fas fa-chart-bar"></i> Reports</h3>
                <?php if ($accessLevel >= 2): ?>
                    <li><a href="../main/incidents.php"><i class="fas fa-exclamation-circle"></i> Incident Reports</a></li>
                <?php endif; ?>
                <li><a href="../main/incident_table.php"><i class="fas fa-history"></i> Incident History</a></li>
                <li><a href="../main/reports.php"><i class="fas fa-chart-line"></i> Analytics</a></li>
            </ul>
        </aside>
    
    <div class="main-content">
        <header class="main-header">
            <button class="hamburger" id="toggleSidebar">&#9776;</button>
            <div class="header-container">
                <div class="logo">
                    <img src="../../../assets/img/GreenwaterLogo.jpg" alt="Greenwater Village Logo">
                    <h1><span>Greenwater</span> <span>Village</span></h1>
                </div>
                <nav class="nav" id="nav-menu">
                    <form method="POST">
                        <ul class="nav-links">
                            <li>
                                <button class="logout-btn" name="logout">Log Out</button>
                            </li>
                        </ul>
                    </form>
                </nav>
            </div>
        </header>

    <main class="content">
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
                    <form method="POST" action="confirm_household.php" class="household-form">
                        <div class="section">
                            <label for="head"><b>Household Head/Respondent</b></label>
                            <div class="form-group">
                                <label><span style="color: crimson; cursor: help;" title="Required">*</span> First Name: <input type="text" name="household_first_name" class="input-field"></label>
                                <label>Middle Initial: <input type="text" name="household_middle_initial" maxlength="5" class="input-field"></label>
                                <label><span style="color: crimson; cursor: help;" title="Required">*</span> Last Name: <input type="text" name="household_last_name" class="input-field"></label>
                                <label>Suffix: <input type="text" name="household_suffix" maxlength="5" class="input-field"></label>
                            </div>
                        </div>
                        <br>
                        <div class="section">
                            <label for="address"><b>Household Address</b></label>
                            <div class="form-group">
                                <label><span style="color: crimson; cursor: help;" title="Required">*</span> House Number/Code: <input type="text" name="household_number" class="input-field"></label>
                                <label>Purok: <input type="text" name="household_purok" class="input-field"></label>
                                <label>Street: <input type="text" name="household_street" class="input-field"></label>
                            </div>
                            <div class="form-group">
                                <label>District: <input type="text" name="household_district" class="input-field"></label>
                                <label><span style="color: crimson; cursor: help;" title="Required">*</span> Barangay: <input type="text" name="household_barangay" value="Greenwater Village" readonly class="input-field readonly"></label>
                            </div>
                        </div>
                        <br>
                        <div class="section">
                            <label for="num_members"><b>Number of Family Members</b></label>
                            <input type="number" id="num_members" min="1" max="20" style="width:60px;" value="1">
                            <button type="button" name="generate_rows" id="generate-members-btn" class="custom-button">Generate</button>
                        </div>

                        <div id="family-members-container" class="section"></div>

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
                        <div class="form-actions">
                            <button type="submit" name="save-household" class="custom-button">Save Household</button>
                            <button type="button" class="custom-button gray" id="go-back-btn">Return to Residency Management</button>
                        </div>
                    </form>
                </div>
            </div>
    </main>
    <footer class="main-footer">
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
    </div>
    </div>
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
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

        document.getElementById('go-back-btn').addEventListener('click', function() {
            window.location.href = '../../main/residency_management.php';
        });
        </script>
        <style>
            .household-form {
                background-color: #ffffff;
                padding: 25px 30px;
                max-width: 1000px;
                margin: 30px auto;
                border: 1px solid #dceee2;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            }

            .section {
                margin-bottom: 24px;
            }

            .section > label {
                font-weight: bold;
                display: block;
                margin-bottom: 10px;
                color: #3c6b56;
            }

            .form-group {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                margin-bottom: 10px;
            }

            .form-group label {
                flex: 1 1 200px;
                font-size: 14px;
                color: #2e3c36;
            }

            .input-field {
                width: 100%;
                padding: 8px 10px;
                margin-top: 4px;
                border: 1px solid #cde5d7;
                border-radius: 4px;
                background-color: #ffffff;
                transition: border-color 0.2s;
            }

            .input-field:focus {
                border-color: #4ca471;
                outline: none;
            }

            .input-field.readonly {
                background-color: #f2f7f3;
                color: #666;
                cursor: not-allowed;
            }

            /* Buttons */
            .custom-button {
                background-color: #4ca471;
                color: white;
                border: none;
                padding: 10px 18px;
                font-size: 14.5px;
                border-radius: 5px;
                cursor: pointer;
                margin-top: 10px;
                transition: background-color 0.3s;
            }

            .custom-button:hover {
                background-color: #3e8d61;
            }

            .custom-button.gray {
                background-color: #aaaaaa;
            }

            .custom-button.gray:hover {
                background-color: #888888;
            }

            .form-actions {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                margin-top: 30px;
            }
        </style>
</body>
</html>