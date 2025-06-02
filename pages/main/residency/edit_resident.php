<?php
    include '../../../config/dbfetch.php';
    
    // access level verification
    if (!isset($_SESSION['user_id']) || $accessLevel < 2) {
        header("Location: ../dashboard.php");
        exit;
    }

    if (isset($_POST['go-back'])) {
        header("Location: ../../main/residency_management.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit-resident'])) {
        $residentID = $_POST['resident_id'] ?? '';
        $editResidentQuery = "SELECT * FROM family_members WHERE member_id = :member_id";
        $editResidentStmt = $pdo->prepare($editResidentQuery);
        $editResidentStmt->execute(['member_id' => $residentID]);
        $residentData = $editResidentStmt->fetch();
    }

    if (isset($_POST['update-resident'])) {
        $residentID = $_POST['resident_id'] ?? '';
        $fname = $_POST['fname'] ?? '';
        $mname = $_POST['mname'] ?? '';
        $lname = $_POST['lname'] ?? '';
        $suffix = $_POST['suffix'] ?? '';
        $sex = $_POST['sex'] ?? '';
        $birthdate = $_POST['birthdate'] ?? '';
        $civilStatus = $_POST['civilstatus'] ?? '';
        $religion = $_POST['religion'] ?? '';
        $schooling = $_POST['schooling'] ?? '';
        $attainment = $_POST['attainment'] ?? '';
        $occupation = $_POST['occupation'] ?? '';
        $livelihood_training = $_POST['livelihood_training'] ?? '';
        $emp_status = $_POST['emp_status'] ?? '';
        $emp_category = $_POST['emp_category'] ?? '';
        $income_cash = $_POST['income_cash'] ?? '';
        $income_kind = $_POST['income_kind'] ?? '';

        $is_senior_citizen = 0;
        if ($birthdate) {
            $birthDateObj = new DateTime($birthdate);
            $today = new DateTime();
            $age = $today->diff($birthDateObj)->y;
            if ($age >= 60) {
                $is_senior_citizen = 1;
            }
        }

        $is_pwd = isset($_POST['is_pwd']) ? 1 : 0;
        $is_ofw = isset($_POST['is_ofw']) ? 1 : 0;
        $is_solo_parent = isset($_POST['is_solo_parent']) ? 1 : 0;
        $is_indigenous = isset($_POST['is_indigenous']) ? 1 : 0;
        $remarks = $_POST['remarks'] ?? '';

        try {
            $pdo->beginTransaction();

            $updateResidentQuery = "UPDATE family_members SET 
                first_name = :fname, 
                middle_initial = :mname, 
                last_name = :lname, 
                suffix = :suffix,
                sex = :sex,
                birthdate = :birthdate,
                civil_status = :civil_status,
                religion = :religion,
                schooling = :schooling,
                attainment = :attainment,
                occupation = :occupation,
                emp_status = :emp_status,
                emp_category = :emp_category,
                income_cash = :income_cash,
                income_kind = :income_kind,
                livelihood_training = :livelihood_training,
                is_senior_citizen = :is_senior_citizen,
                is_pwd = :is_pwd,
                is_ofw = :is_ofw,
                is_solo_parent = :is_solo_parent,
                is_indigenous = :is_indigenous,
                remarks = :remarks
                WHERE member_id = :member_id
            ";
            $updateResidentStmt = $pdo->prepare($updateResidentQuery);
            $updateResidentStmt->execute([
                'fname' => $fname,
                'mname' => $mname,
                'lname' => $lname,
                'suffix' => $suffix,
                'sex' => $sex,
                'birthdate' => $birthdate,
                'civil_status' => $civilStatus,
                'religion' => $religion,
                'schooling' => $schooling,
                'attainment' => $attainment,
                'occupation' => $occupation,
                'livelihood_training' => $livelihood_training,
                'emp_status' => $emp_status,
                'emp_category' => $emp_category,
                'income_cash' => $income_cash,
                'income_kind' => $income_kind,
                'is_senior_citizen' => $is_senior_citizen,
                'is_pwd' => $is_pwd,
                'is_ofw' => $is_ofw,
                'is_solo_parent' => $is_solo_parent,
                'is_indigenous' => $is_indigenous,
                'remarks' => $remarks,
                'member_id' => $residentID
            ]);

            // After updating, re-fetch resident data:
            $residentID = $_POST['resident_id'] ?? '';
            $editResidentQuery = "SELECT * FROM family_members WHERE member_id = :member_id";
            $editResidentStmt = $pdo->prepare($editResidentQuery);
            $editResidentStmt->execute(['member_id' => $residentID]);
            $residentData = $editResidentStmt->fetch();

            $committed = $pdo->commit();
            if ($committed) {
                echo "
                    <link rel='stylesheet' href='../../../assets/css/style.css'>
                    <script src='js/sweetalert2.js'></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                title: 'Update resident.',
                                text: 'Do you want to update the resident details?',
                                icon: 'info',
                                showCancelButton: true,
                                confirmButtonColor: 'green',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Yes, update it.',
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire({
                                        title: 'Resident updated.',
                                        text: 'Resident has been successfully updated.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        window.location.href = '../residency_management.php';
                                    });
                                }
                            });
                        });
                    </script>
                ";
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error: " . $e->getMessage());
            echo "
                <link rel='stylesheet' href='../../../assets/css/style.css'>
                <script src='js/sweetalert2.js'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Error occurred.',
                            text: 'An error occurred while updating the resident details. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href='../residency_management.php';
                        });
                    });
                </script>
            ";
            exit;
        }
    }

    // if not set, set default to avoid undefined variable error
    if (!isset($editResidentStmt)) {
        $editResidentStmt = new stdClass();
        $editResidentStmt->rowCount = function() { 
            return 0; 
        };
    }
    if (!isset($residentData)) {
        $residentData = [];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <script src="js/sweetalert2.js"></script>
    <title>UBISH Dashboard | Edit Resident</title>
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
        .custom-cancel-button {
            border: 2px solid gray;
            background-color: white;
            color: black;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .custom-cancel-button:hover {
            background-color: lightgray;
        }
        .edit-residency-input {
            width: 100%;
            padding: 8px;
            font-size: medium;
            border: 1px solid gray;
            border-radius: 4px;
        }
        .member-information-actions {
            margin: 16px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .member-information-actions button {
            margin: 0 8px;
            padding: 8px 16px;
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
                <center>
                    <h1>Update Resident Information</h1>
                </center>
                <div class="member-information">
                    <form method="POST">
                        <div class="member-information-actions">
                            <input type="hidden" name="resident_id" value="<?php echo htmlspecialchars($residentData['member_id'] ?? ''); ?>">
                            <button type="submit" name="update-resident" class="custom-cancel-button">Update Resident</button>
                            <button type="button" id="go-back-btn" class="custom-cancel-button">Go Back</button>
                        </div>
                        <?php 
                            if ($editResidentStmt->rowCount() < 1) {
                                echo '<br><p>No resident information found.</p>';
                            } else {
                        ?>
                        <div class="family-member" style="border:1px solid #ccc; margin: 24px auto; border-radius:8px; padding:16px;">
                            <table border="1" cellspacing="0" style="width:100%; table-layout:fixed;">
                                <tr>
                                    <th>First Name</th>
                                    <td>
                                        <input class="edit-residency-input" type="text" name="fname" value="<?php echo htmlspecialchars($residentData['first_name']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Middle Initial</th>
                                    <td>
                                        <input class="edit-residency-input" type="text" name="mname" value="<?php echo htmlspecialchars($residentData['middle_initial']); ?>" maxlength="5">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Name</th>
                                    <td>
                                        <input class="edit-residency-input" type="text" name="lname" value="<?php echo htmlspecialchars($residentData['last_name']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Suffix</th>
                                    <td>
                                        <input class="edit-residency-input" type="text" name="suffix" value="<?php echo htmlspecialchars($residentData['suffix']); ?>" maxlength="10">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Sex</th>
                                    <td>
                                        <select name="sex" class="edit-residency-input">
                                            <option value="" disabled>-- SELECT SEX</option>
                                            <option value="M" <?php echo ($residentData['sex'] === 'M') ? "selected" : ''; ?>>Male</option>
                                            <option value="F" <?php echo ($residentData['sex'] === 'F') ? "selected" : ''; ?>>Female</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date of Birth (<i>dd-mm-yyyy</i>)</th>
                                    <td>
                                        <input class="edit-residency-input" type="date" name="birthdate" value="<?php echo htmlspecialchars($residentData['birthdate']); ?>" class="birthdate-input">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Age</th>
                                    <td>
                                        <span id="age-display" style="padding: 4px;"></span>
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

                                            document.addEventListener('DOMContentLoaded', function() {
                                                var birthdateInput = document.querySelector('input[name="birthdate"]');
                                                var ageDisplay = document.getElementById('age-display');
                                                function updateAge() {
                                                    ageDisplay.textContent = calculateAge(birthdateInput.value);
                                                }
                                                if (birthdateInput) {
                                                    updateAge();
                                                    birthdateInput.addEventListener('input', updateAge);
                                                }
                                            });
                                        </script>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Civil Status</th>
                                    <td>
                                        <select name="civilstatus" class="edit-residency-input">
                                            <option value="" disabled>-- SELECT CIVIL STATUS</option>
                                            <option value="Single" <?php echo ($residentData['civil_status'] === 'Single') ? "selected" : ''; ?>>Single</option>
                                            <option value="Married" <?php echo ($residentData['civil_status'] === 'Married') ? "selected" : ''; ?>>Married</option>
                                            <option value="Widowed/r" <?php echo ($residentData['civil_status'] === 'Widowed/r') ? "selected" : ''; ?>>Widowed/r</option>
                                            <option value="Separated" <?php echo ($residentData['civil_status'] === 'Separated') ? "selected" : ''; ?>>Separated</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Religion</th>
                                    <td>
                                        <select name="religion" class="edit-residency-input">
                                            <option value="" disabled>-- SELECT RELIGION</option>
                                            <option value="Roman Catholic" <?php echo ($residentData['religion'] === "Roman Catholic") ? "selected" : ''; ?>>Roman Catholic</option>
                                            <option value="Iglesia ni Cristo" <?php echo ($residentData['religion'] === "Iglesia ni Cristo") ? "selected" : ''; ?>>Iglesia ni Cristo</option>
                                            <option value="Islam" <?php echo ($residentData['religion'] === "Islam") ? "selected" : ''; ?>>Islam</option>
                                            <option value="Seventh Day Adventist" <?php echo ($residentData['religion'] === "Seventh Day Adventist") ? "selected" : ''; ?>>Seventh Day Adventist</option>
                                            <option value="Methodist" <?php echo ($residentData['religion'] === "Methodist") ? "selected" : ''; ?>>Methodist</option>
                                            <option value="Other" <?php echo ($residentData['religion'] === "Other") ? "selected" : ''; ?>>Other</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Schooling</th>
                                    <td>
                                        <select name="schooling" class="edit-residency-input">
                                            <option value="" disabled>-- SELECT SCHOOLING</option>
                                            <option value="In school" <?php echo ($residentData['schooling'] === 'In school') ? "selected" : ''; ?>>In school</option>
                                            <option value="Out of school" <?php echo ($residentData['schooling'] === 'Out of school') ? "selected" : ''; ?>>Out of school</option>
                                            <option value="Not yet in school" <?php echo ($residentData['schooling'] === 'Not yet in school') ? "selected" : ''; ?>>Not yet in school</option>
                                            <option value="Graduate" <?php echo ($residentData['schooling'] === 'Graduate') ? "selected" : ''; ?>>Graduate</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Highest Educational Attainment</th>
                                    <td>
                                        <select name="attainment" class="edit-residency-input">
                                            <option value="" disabled>-- SELECT ATTAINMENT</option>
                                            <option value="Elementary" <?php echo ($residentData['attainment'] === 'Elementary') ? "selected" : ''; ?>>Elementary</option>
                                            <option value="High School" <?php echo ($residentData['attainment'] === 'High School') ? "selected" : ''; ?>>High School</option>
                                            <option value="College" <?php echo ($residentData['attainment'] === 'College') ? "selected" : ''; ?>>College</option>
                                            <option value="Post-Graduate" <?php echo ($residentData['attainment'] === 'Post-Graduate') ? "selected" : ''; ?>>Post-Graduate</option>
                                            <option value="Vocational" <?php echo ($residentData['attainment'] === 'Vocational') ? "selected" : ''; ?>>Vocational</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Present Job/Occupation</th>
                                    <td>
                                        <input type="text" name="occupation" class="edit-residency-input" value="<?php echo htmlspecialchars($residentData['occupation']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Livelihood Training</th>
                                    <td>
                                        <input type="text" name="livelihood_training" class="edit-residency-input" value="<?php echo htmlspecialchars($residentData['livelihood_training']); ?>" placeholder="Leave blank if none.">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Employment Status</th>
                                    <td>
                                        <select name="emp_status" class="edit-residency-input">
                                            <option value="" disabled>-- SELECT EMPLOYMENT STATUS</option>
                                            <option value="Permanent" <?php echo ($residentData['emp_status'] === "Permanent") ? "selected" : ''; ?>>Permanent</option>
                                            <option value="Temporary" <?php echo ($residentData['emp_status'] === "Temporary") ? "selected" : ''; ?>>Temporary</option>
                                            <option value="Contractual" <?php echo ($residentData['emp_status'] === "Contractual") ? "selected" : ''; ?>>Contractual</option>
                                            <option value="Self-Employed" <?php echo ($residentData['emp_status'] === "Self-Employed") ? "selected" : ''; ?>>Self-Employed</option>
                                            <option value="Unemployed" <?php echo ($residentData['emp_status'] === "Unemployed") ? "selected" : ''; ?>>Unemployed</option>
                                            <option value="Others" <?php echo ($residentData['emp_status'] === "Others") ? "selected" : ''; ?>>Others</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Employment Category</th>
                                    <td>
                                        <select name="emp_category" class="edit-residency-input">
                                            <option value="" disabled>-- SELECT EMPLOYMENT CATEGORY</option>
                                            <option value="Private" <?php echo ($residentData['emp_category'] === "Private") ? "selected" : ''; ?>>Private</option>
                                            <option value="Government" <?php echo ($residentData['emp_category'] === "Government") ? "selected" : ''; ?>>Government</option>
                                            <option value="Self-Employed" <?php echo ($residentData['emp_category'] === "Self-Employed") ? "selected" : ''; ?>>Self-Employed</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Estimated Monthly Income 
                                        (Cash in <span style="cursor: help;" title="Philippine Peso">₱</span>)
                                    </th>
                                    <td>
                                        <input type=number" name="income_cash" class="edit-residency-input" value="<?php echo htmlspecialchars($residentData['income_cash']); ?>" step="0.01" min="0" placeholder="0.00">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estimated Monthly Income (Kind)</th>
                                    <td>
                                        <input type="text" name="income_kind" class="edit-residency-input" value="<?php echo htmlspecialchars($residentData['income_kind']); ?>" placeholder="e.g. Rice, Vegetables, etc. Leave blank if none.">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Others</th>
                                    <td>
                                        <div class="indicate-others">
                                            <input type="checkbox" name="is_pwd" <?php echo ($residentData['is_pwd'] == 1) ? "checked" : ''; ?>>&nbsp;Person with Disability (PWD)<br>
                                            <input type="checkbox" name="is_ofw" <?php echo ($residentData['is_ofw'] == 1) ? "checked" : ''; ?>>&nbsp;Overseas Filipino Worker (OFW)<br>
                                            <input type="checkbox" name="is_solo_parent" <?php echo ($residentData['is_solo_parent'] == 1) ? "checked" : ''; ?>>&nbsp;Solo Parent<br>
                                            <input type="checkbox" name="is_indigenous" <?php echo ($residentData['is_indigenous'] == 1) ? "checked" : ''; ?>>&nbsp;Indigenous Person (IP)<br>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td>
                                        <textarea name="remarks" class="edit-residency-input" placeholder="Enter remarks here or leave blank if none." style="width:100%; resize: vertical; max-height: 256px; padding: 8px;"><?php echo htmlspecialchars($residentData['remarks']); ?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php } ?>
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
        // input validations
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.member-information form');
            if (!form) return;

            // Go Back button handler
            const goBackBtn = document.getElementById('go-back-btn');
            if (goBackBtn) {
                goBackBtn.addEventListener('click', function() {
                    window.location.href = '../../main/residency_management.php';
                });
            }

            form.addEventListener('submit', function(e) {
                // Only validate if Update Resident is clicked
                if (document.activeElement && document.activeElement.name !== 'update-resident') return;

                // Get values
                const fname = form.querySelector('input[name="fname"]')?.value.trim();
                const lname = form.querySelector('input[name="lname"]')?.value.trim();
                const sex = form.querySelector('select[name="sex"]')?.value;
                const birthdate = form.querySelector('input[name="birthdate"]')?.value;
                const civil = form.querySelector('select[name="civilstatus"]')?.value;
                const schooling = form.querySelector('select[name="schooling"]')?.value;
                const emp = form.querySelector('select[name="emp_status"]')?.value;

                let hasError = false;
                let errorMsg = '';

                if (!fname || !lname || !sex || !birthdate || !civil || !schooling || !emp) {
                    hasError = true;
                    errorMsg = 'Please fill out all required fields:<br>';
                    if (!fname) errorMsg += 'First Name<br>';
                    if (!lname) errorMsg += 'Last Name<br>';
                    if (!sex) errorMsg += 'Sex<br>';
                    if (!birthdate) errorMsg += 'Birthdate<br>';
                    if (!civil) errorMsg += 'Civil Status<br>';
                    if (!schooling) errorMsg += 'Schooling<br>';
                    if (!emp) errorMsg += 'Employment Status<br>';
                }

                if (birthdate && new Date(birthdate) > new Date()) {
                    hasError = true;
                    errorMsg = 'Birthdate cannot be in the future.';
                }

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
        });
    </script>
</body>
</html>