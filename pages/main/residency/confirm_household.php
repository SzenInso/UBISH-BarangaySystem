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
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Greenwater Village Dashboard | Confirm Household</title>
</head>
<body>
    <style>
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
    </style>

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
                <center>
                    <h1>Confirm Household Information</h1>
                </center>
                <div class="member-submission">
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
                        <p>Review the household information below. If everything is correct, click the "<b>Confirm</b>" button to finalize the submission.</p>
                        <p>Otherwise, click the "<b>Go Back</b>" button to return to the previous page.</p>
                    </div>
                    <form method="POST" action="finalize_household.php">
                        <?php 
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save-household'])) {
                        ?>
                                <div class="household" style="border:1px solid #ccc; margin-bottom:24px; border-radius:8px; padding:16px;">
                                    <h3>Household Information</h3>

                                    <input type="hidden" name="household_first_name" value="<?php echo htmlspecialchars($_POST['household_first_name'] ?? ''); ?>">
                                    <input type="hidden" name="household_middle_initial" value="<?php echo htmlspecialchars($_POST['household_middle_initial'] ?? ''); ?>">
                                    <input type="hidden" name="household_last_name" value="<?php echo htmlspecialchars($_POST['household_last_name'] ?? ''); ?>">
                                    <input type="hidden" name="household_suffix" value="<?php echo htmlspecialchars($_POST['household_suffix'] ?? ''); ?>">
                                    <input type="hidden" name="household_number" value="<?php echo htmlspecialchars($_POST['household_number'] ?? ''); ?>">
                                    <input type="hidden" name="household_purok" value="<?php echo htmlspecialchars($_POST['household_purok'] ?? ''); ?>">
                                    <input type="hidden" name="household_street" value="<?php echo htmlspecialchars($_POST['household_street'] ?? ''); ?>">
                                    <input type="hidden" name="household_district" value="<?php echo htmlspecialchars($_POST['household_district'] ?? ''); ?>">
                                    <input type="hidden" name="household_barangay" value="<?php echo htmlspecialchars($_POST['household_barangay'] ?? ''); ?>">

                                    <table border="1" cellspacing="0" style="width:100%; table-layout:fixed;">
                                        <tr>
                                            <th>Household Head/Respondent</th>
                                            <td>
                                                <?php 
                                                    echo isset($_POST['household_first_name']) && isset($_POST['household_last_name']) ? 
                                                    htmlspecialchars($_POST['household_first_name']) . " " . htmlspecialchars(strtoupper($_POST['household_middle_initial'])) . " " .
                                                    htmlspecialchars($_POST['household_last_name']) . " " . htmlspecialchars($_POST['household_suffix'])
                                                    : ''; 
                                                ?>
                                            </td>
                                        <tr>
                                            <th>House Number/Code</th>
                                            <td><?php echo isset($_POST['household_number']) ? htmlspecialchars($_POST['household_number']) : ''; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Purok</th>
                                            <td><?php echo isset($_POST['household_purok']) ? htmlspecialchars($_POST['household_purok']) : ''; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Street</th>
                                            <td><?php echo isset($_POST['household_street']) ? htmlspecialchars($_POST['household_street']) : ''; ?></td>
                                        </tr>
                                        <tr>
                                            <th>District</th>
                                            <td><?php echo isset($_POST['household_district']) ? htmlspecialchars($_POST['household_district']) : ''; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Barangay</th>
                                            <td><?php echo isset($_POST['household_barangay']) ? htmlspecialchars($_POST['household_barangay']) : ''; ?></td>
                                        </tr>
                                    </table>
                                </div>
                        <?php
                                $num_members = isset($_POST['fname']) ? count($_POST['fname']) : 0;
                                for ($i = 0; $i < $num_members; $i++) {
                                    
                        ?>
                                    <div class="family-member" style="border:1px solid #ccc; margin-bottom:24px; border-radius:8px; padding:16px;">
                                        <h3>Family Member <?php echo $i+1; ?> </h3>

                                        <input type="hidden" readonly name="member_index[]" value="<?php echo $i; ?>"> <!-- for family member identifier -->
                                        <input type="hidden" name="fname[]" value="<?php echo htmlspecialchars($_POST['fname'][$i] ?? ''); ?>">
                                        <input type="hidden" name="mname[]" value="<?php echo htmlspecialchars($_POST['mname'][$i] ?? ''); ?>">
                                        <input type="hidden" name="lname[]" value="<?php echo htmlspecialchars($_POST['lname'][$i] ?? ''); ?>">
                                        <input type="hidden" name="suffix[]" value="<?php echo htmlspecialchars($_POST['suffix'][$i] ?? ''); ?>">
                                        <input type="hidden" name="relation[]" value="<?php echo htmlspecialchars($_POST['relation'][$i] ?? ''); ?>">
                                        <input type="hidden" name="sex[]" value="<?php echo htmlspecialchars($_POST['sex'][$i] ?? ''); ?>">
                                        <input type="hidden" name="birthdate[]" value="<?php echo htmlspecialchars($_POST['birthdate'][$i] ?? ''); ?>">
                                        <input type="hidden" name="civilstatus[]" value="<?php echo htmlspecialchars($_POST['civilstatus'][$i] ?? ''); ?>">
                                        <input type="hidden" name="religion[]" value="<?php echo htmlspecialchars($_POST['religion'][$i] ?? ''); ?>">
                                        <input type="hidden" name="schooling[]" value="<?php echo htmlspecialchars($_POST['schooling'][$i] ?? ''); ?>">
                                        <input type="hidden" name="attainment[]" value="<?php echo htmlspecialchars($_POST['attainment'][$i] ?? ''); ?>">
                                        <input type="hidden" name="occupation[]" value="<?php echo htmlspecialchars($_POST['occupation'][$i] ?? ''); ?>">
                                        <input type="hidden" name="emp_status[]" value="<?php echo htmlspecialchars($_POST['emp_status'][$i] ?? ''); ?>">
                                        <input type="hidden" name="emp_category[]" value="<?php echo htmlspecialchars($_POST['emp_category'][$i] ?? ''); ?>">
                                        <input type="hidden" name="income_cash[]" value="<?php echo htmlspecialchars($_POST['income_cash'][$i] ?? ''); ?>">
                                        <input type="hidden" name="income_type[]" value="<?php echo htmlspecialchars($_POST['income_type'][$i] ?? ''); ?>">
                                        <input type="hidden" name="livelihood_training[]" value="<?php echo htmlspecialchars($_POST['livelihood_training'][$i] ?? ''); ?>">
                                        <?php if ((isset($_POST['is_PWD'][$i]))) { ?>
                                            <input type="hidden" name="is_PWD[<?php echo $i; ?>]" value="1">
                                        <?php } else { ?>
                                            <input type="hidden" name="is_PWD[<?php echo $i; ?>]" value="0">
                                        <?php } ?>
                                        <?php if ((isset($_POST['is_OFW'][$i]))) { ?>
                                            <input type="hidden" name="is_OFW[<?php echo $i; ?>]" value="1">
                                        <?php } else { ?>
                                            <input type="hidden" name="is_OFW[<?php echo $i; ?>]" value="0">    
                                        <?php } ?>
                                        <?php if ((isset($_POST['is_solo_parent'][$i]))) { ?>
                                            <input type="hidden" name="is_solo_parent[<?php echo $i; ?>]" value="1">
                                        <?php } else { ?> 
                                            <input type="hidden" name="is_solo_parent[<?php echo $i; ?>]" value="0">
                                        <?php } ?>
                                        <?php if ((isset($_POST['is_indigenous'][$i]))) { ?>
                                            <input type="hidden" name="is_indigenous[<?php echo $i; ?>]" value="1">
                                        <?php } else { ?> 
                                            <input type="hidden" name="is_indigenous[<?php echo $i; ?>]" value="0">
                                        <?php } ?>
                                        <input type="hidden" name="remarks[]" value="<?php echo htmlspecialchars($_POST['remarks'][$i] ?? ''); ?>">
                                        <table border="1" cellspacing="0" style="width:100%; table-layout:fixed;">
                                            <tr>
                                                <th>Full Name</th>
                                                <td>
                                                    <?php 
                                                        if (isset($_POST['fname'][$i]) && isset($_POST['lname'][$i])) {
                                                            echo htmlspecialchars($_POST['fname'][$i]) . " " . htmlspecialchars(strtoupper($_POST['mname'][$i] ?? '')) . " " .
                                                                htmlspecialchars($_POST['lname'][$i]) . " " . htmlspecialchars($_POST['suffix'][$i] ?? '');
                                                        } else { echo 'Not specified'; }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Relation</th>
                                                <td><?php echo isset($_POST['relation'][$i]) ? htmlspecialchars($_POST['relation'][$i]) : ''; ?></td>
                                            </tr>
                                            <tr>
                                            <tr>
                                                <th>Sex</th>
                                                <td>
                                                    <?php 
                                                        if (isset($_POST['sex'][$i])) { echo ($_POST['sex'][$i] === 'M') ? "Male" : "Female"; } 
                                                        else { echo "Not specified"; }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Date of Birth</th>
                                                <td><?php echo isset($_POST['birthdate'][$i]) ? htmlspecialchars($_POST['birthdate'][$i]) : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Age</th>
                                                <td>
                                                    <?php 
                                                        if (isset($_POST['birthdate'][$i])) {
                                                            $birthdate = new DateTime($_POST['birthdate'][$i]);
                                                            $today = new DateTime();
                                                            $age = $today->diff($birthdate)->y;
                                                            echo $age . " year(s) old";
                                                        } else {
                                                            echo 'Not specified';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Civil Status</th>
                                                <td><?php echo isset($_POST['civilstatus'][$i]) ? htmlspecialchars($_POST['civilstatus'][$i]) : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Religion</th>
                                                <td><?php echo isset($_POST['religion'][$i]) ? htmlspecialchars($_POST['religion'][$i]) : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Schooling</th>
                                                <td><?php echo isset($_POST['schooling'][$i]) ? htmlspecialchars($_POST['schooling'][$i]) : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Highest Educational Attainment</th>
                                                <td><?php echo isset($_POST['attainment'][$i]) ? htmlspecialchars($_POST['attainment'][$i]) : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Present Job/Occupation</th>
                                                <td><?php echo isset($_POST['occupation'][$i]) ? htmlspecialchars($_POST['occupation'][$i]) : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Livelihood Training</th>
                                                <td><?php echo isset($_POST['livelihood_training'][$i]) ? htmlspecialchars($_POST['livelihood_training'][$i]) : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Employment Status</th>
                                                <td><?php echo isset($_POST['emp_status'][$i]) ? htmlspecialchars($_POST['emp_status'][$i]) : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Employment Category</th>
                                                <td><?php echo isset($_POST['emp_category'][$i]) ? htmlspecialchars($_POST['emp_category'][$i]) : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Estimated Monthly Income (Cash)</th>
                                                <td>₱&nbsp;<?php echo isset($_POST['income_cash'][$i]) ? htmlspecialchars($_POST['income_cash'][$i]) : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Estimated Monthly Income (Kind)</th>
                                                <td><?php echo isset($_POST['income_type'][$i]) ? htmlspecialchars($_POST['income_type'][$i]) : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Is a Senior Citizen</th>
                                                <td>
                                                    <?php 
                                                        if (isset($_POST['birthdate'][$i])) {
                                                            $birthdate = new DateTime($_POST['birthdate'][$i]);
                                                            $today = new DateTime();
                                                            $age = $today->diff($birthdate)->y;
                                                            echo $age >= 60 ? 'Yes' : 'No';
                                                        } else {
                                                            echo 'No';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Is a Person with Disability (PWD)?</th>
                                                <td><?php echo isset($_POST['is_PWD'][$i]) ? 'Yes' : 'No'; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Is an Overseas Filipino Worker (OFW)?</th>
                                                <td><?php echo isset($_POST['is_OFW'][$i]) ? 'Yes' : 'No'; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Is a Solo Parent?</th>
                                                <td><?php echo isset($_POST['is_solo_parent'][$i]) ? 'Yes' : 'No'; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Is an Indigenous Person (IP)?</th>
                                                <td><?php echo isset($_POST['is_indigenous'][$i]) ? 'Yes' : 'No'; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td><?php echo isset($_POST['remarks'][$i]) ? htmlspecialchars($_POST['remarks'][$i]) : ''; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                        <?php
                                }
                            }
                        ?>
                        <div class="member-submission-btns">
                            <button class="custom-cancel-button" type="submit" name="confirm-household">Confirm</button>
                            <button class="custom-cancel-button" type="button" onclick="window.history.back();">Go Back</button>
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
</body>
</html>