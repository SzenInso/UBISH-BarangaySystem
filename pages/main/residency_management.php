<?php
    include '../../config/dbfetch.php';
    
    // access level verification
    if (!isset($_SESSION['user_id']) || $accessLevel < 2) {
        header("Location: ../main/dashboard.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>UBISH Dashboard | Residency Management</title>
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
                <img src="../../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
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
                    <li><a href="../main/dashboard.php">Home</a></li>
                    <li><a href="../main/account.php">Account</a></li>
                    
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../main/documents.php">Documents</a></li>
                    <li><a href="../main/announcements.php">Post Announcement</a></li>
                    
                    <h3>Tables & Requests</h3>
                    <li><a href="../main/employee_table.php">Employee Table</a></li>

                    <!-- STANDARD -->
                    <?php if ($accessLevel >= 2) { echo '<li class="active"><a href="../main/residency_management.php">Residency Management</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/certificates.php">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/permits.php">Permit Requests</a></li>'; } ?>
                    <!-- STANDARD -->
                    
                    <!-- FULL -->
                    <?php if ($accessLevel >= 3) { echo '<li><a href="../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <!-- FULL -->
                    
                    <h3>Reports</h3>
                    <!-- STANDARD -->
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/incidents.php">Incident Reports</a></li>'; }  ?>
                    <!-- STANDARD -->
                    
                    <li><a href="../main/incident_table.php">Incident History</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <center><h1>Residency Management</h1></center>
                <?php 
                    $residencyQuery = "SELECT * FROM family_members ORDER BY last_name, first_name";
                    $residencyStmt = $pdo->query($residencyQuery);
                    $residency = $residencyStmt->fetchAll();
                ?>
                <div class="residency-table-actions">
                    <label for="view-by">View By:</label>
                    <select id="residency-view">
                        <option>Residency</option>
                        <option>Household</option>
                    </select>
                    <label for="sort-by">Sort By:</label>
                    <select id="residency-sort">
                        <option id="id-asc">ID (Ascending)</option>
                        <option id="id-desc">ID (Descending)</option>
                        <option id="name-asc">Last Name (Ascending)</option>
                        <option id="name-desc">Last Name (Descending)</option>
                        <option id="bdate-asc">Birthdate (Ascending)</option>
                        <option id="bdate-desc">Birthdate (Descending)</option>
                    </select>
                </div>
                <div class="residency-table">
                    <table border="1" cellpadding="10" cellspacing="0">
                        <thead>
                            <th>Residency ID</th>
                            <th>Name</th>
                            <th>Sex</th>
                            <th>Birhtdate</th>
                            <th>Age</th>
                            <th>Civil Status</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($residency as $member) {
                                    $r_id = htmlspecialchars($member['member_id']);
                                    $name = htmlspecialchars($member['last_name'] . ', ' . $member['first_name']);
                                    $sex = ($member['sex'] === 'M') ? "Male" : "Female";
                                    $birthdate = htmlspecialchars(date('F j, Y', strtotime($member['birthdate'])));
                                    $age = date_diff(date_create($birthdate), date_create('today'))->y;
                                    $civilStatus = htmlspecialchars($member['civil_status']);
                            ?>
                                    <tr>
                                        <td><?php echo $r_id; ?></td>
                                        <td><?php echo $name; ?></td>
                                        <td><?php echo $sex; ?></td>
                                        <td><?php echo $birthdate; ?></td>
                                        <td><?php echo $age; ?></td>
                                        <td><?php echo $civilStatus; ?></td>
                                        <td>
                                            <div class="residency-actions">
                                                <form action="" method="POST">
                                                    <button>View More</button>
                                                </form>
                                                <form action="" method="POST">
                                                    <button>Update Resident</button>
                                                </form>
                                                <form action="" method="POST">
                                                    <button>Delete Resident</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php 
                    $familyQuery = "
                        SELECT * FROM households
                        JOIN household_addresses ON households.household_address_id = household_addresses.household_address_id
                        JOIN household_respondents ON households.household_respondent_id = household_respondents.household_respondent_id
                        JOIN families ON households.household_id = families.household_id
                    ";
                    $familyStmt = $pdo->query($familyQuery);
                    $families = $familyStmt->fetchAll();


                ?>
                <div class="residency-family">
                    <table border="1" cellpadding="10" cellspacing="0">
                        <thead>
                            <th>Household ID</th>
                            <th>Address</th>
                            <th>Respondent</th>
                            <th>Family ID</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($families as $family) {
                                    $householdId = htmlspecialchars($family['household_id']);
                                    $addressParts = [];
                                    if (!empty($family['house_number'])) { $addressParts[] = htmlspecialchars($family['house_number']); } // add house number if available
                                    if (!empty($family['purok'])) { $addressParts[] = 'Purok ' . htmlspecialchars($family['purok']); } // add purok if available
                                    if (!empty($family['street'])) { $addressParts[] = htmlspecialchars($family['street']); } // add street if available
                                    if (!empty($family['district'])) { $addressParts[] = 'District ' . htmlspecialchars($family['district']); } // add district if available
                                    if (!empty($family['barangay'])) { $addressParts[] = htmlspecialchars($family['barangay']); } // add barangay if available
                                    $address = implode(', ', $addressParts); // combine all address parts
                                    $respondent = htmlspecialchars($family['first_name']) . ' ' . htmlspecialchars($family['last_name']);
                                    $familyId = htmlspecialchars($family['family_id']);
                            ?>
                                    <tr>
                                        <td><?php echo $householdId; ?></td>
                                        <td><?php echo $address; ?></td>
                                        <td><?php echo $respondent; ?></td>
                                        <td><?php echo $familyId; ?></td>
                                        <td>
                                            <form action="">
                                                <button>View More</button>
                                            </form>
                                            <form action="">
                                                <button>Update Household</button>
                                            </form>
                                            <form action="">
                                                <button>Delete Household</button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
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
