<?php
    include '../../../config/dbfetch.php';
    include '../../../baseURL.php';
    // access level verification
    if (!isset($_SESSION['user_id']) || $accessLevel < 2) {
        header("Location: ../dashboard.php");
        exit;
    }

    if (isset($_POST['go-back'])) {
        header("Location: ../residency_management.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view-household'])) {
        $householdId = $_POST['household_id'] ?? null;
        $viewHouseholdQuery = "
            SELECT hh.*, hr.*, ha.* FROM households hh
            JOIN household_addresses ha ON hh.household_address_id = ha.household_address_id
            JOIN household_respondents hr ON hh.household_respondent_id = hr.household_respondent_id
            WHERE hh.household_id = :household_id
        ";
        $viewHouseholdStmt = $pdo->prepare($viewHouseholdQuery);
        $viewHouseholdStmt->execute(['household_id' => $householdId]);
        $householdData = $viewHouseholdStmt->fetch();

        $viewFamilyQuery = "
            SELECT fa.family_id, fm.* FROM households hh
            JOIN household_addresses ha ON hh.household_address_id = ha.household_address_id
            JOIN household_respondents hr ON hh.household_respondent_id = hr.household_respondent_id
            JOIN families fa ON hh.household_id = fa.household_id
            JOIN family_members fm ON fa.family_id = fm.family_id
            WHERE hh.household_id = :household_id
        ";
        $viewFamilyStmt = $pdo->prepare($viewFamilyQuery);
        $viewFamilyStmt->execute(['household_id' => $householdId]);
        $familyData = $viewFamilyStmt->fetchAll();
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="../css/dashPages.css">
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Greenwater Village Dashboard | View Household</title>
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
                <center><h1>View Household</h1></center>
                <div class="household-information">
                    <h2>Household Details</h2>
                    <p><strong>Household ID:</strong> <?php echo htmlspecialchars($householdData['household_id']) ?></p>
                    <p><strong>Head/Respondent:</strong> <?php echo htmlspecialchars($householdData['last_name']) . ', ' . htmlspecialchars($householdData['first_name']) . ' ' . htmlspecialchars($householdData['middle_initial']) . '. ' . htmlspecialchars($householdData['suffix']); ?></p>
                    <p><strong>Address:</strong> 
                        <?php 
                            $addressParts = [];
                            if (!empty($householdData['house_number'])) { $addressParts[] = htmlspecialchars($householdData['house_number']); }
                            if (!empty($householdData['purok'])) { $addressParts[] = 'Purok ' . htmlspecialchars($householdData['purok']); }
                            if (!empty($householdData['street'])) { $addressParts[] = htmlspecialchars($householdData['street']); }
                            if (!empty($householdData['district'])) { $addressParts[] = 'District ' . htmlspecialchars($householdData['district']); }
                            if (!empty($householdData['barangay'])) { $addressParts[] = htmlspecialchars($householdData['barangay']); }
                            echo implode(', ', $addressParts);
                        ?>
                    </p>
                    <br>
                    <div class="household-actions">
                        <form method="POST" action="edit_household.php">
                            <input type="hidden" name="household_id" value="<?php echo htmlspecialchars($householdData['household_id']); ?>">
                            <input type="hidden" name="address_id" value="<?php echo htmlspecialchars($householdData['household_address_id']); ?>">
                            <input type="hidden" name="respondent_id" value="<?php echo htmlspecialchars($householdData['household_respondent_id']); ?>">
                            <button class="custom-cancel-button" name="edit-household">Edit Household</button>
                        </form>
                    </div>
                </div>

                <div class="household-information">
                    <h2>Members in this Household</h2>
                    <?php
                    // Fetch all families for this household
                    $familiesQuery = "SELECT family_id FROM families WHERE household_id = :household_id";
                    $familiesStmt = $pdo->prepare($familiesQuery);
                    $familiesStmt->execute(['household_id' => $householdData['household_id']]);
                    $families = $familiesStmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($families) {
                        foreach ($families as $family) {
                            echo '<div class="family-card">';
                            echo '<strong>Family ID:</strong> ' . htmlspecialchars($family['family_id']) . '<br>';
                            // Fetch members for this family
                            $membersQuery = "SELECT first_name, middle_initial, last_name, suffix FROM family_members WHERE family_id = :family_id";
                            $membersStmt = $pdo->prepare($membersQuery);
                            $membersStmt->execute(['family_id' => $family['family_id']]);
                            $members = $membersStmt->fetchAll(PDO::FETCH_ASSOC);

                            if ($members) {
                                echo '<ol class="family-members-list">';
                                foreach ($members as $member) {
                                    $fullName = htmlspecialchars($member['last_name']) . ', ';
                                    $fullName .= htmlspecialchars($member['first_name']);
                                    if (!empty($member['middle_initial'])) {
                                        $fullName .= ' ' . htmlspecialchars($member['middle_initial']) . '.,';
                                    }
                                    if (!empty($member['suffix'])) {
                                        $fullName = rtrim($fullName, ',');
                                        $fullName .= ', ' . htmlspecialchars($member['suffix']);
                                    } else {
                                        $fullName = rtrim($fullName, ',');
                                    }
                                    echo '<li>' . $fullName . '</li>';
                                }
                                echo '</ol>';
                            } else {
                                echo '<div class="no-members">No family members found.</div>';
                            }
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="no-families">No families found for this household.</div>';
                    }
                    ?>
                </div>
                <div class="resident-actions">
                    <form method="POST">
                        <button class="custom-cancel-button" name="go-back">Go Back</button>
                    </form>
                </div>
            </div>
    </main>
            <footer class="main-footer">
                <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
            </footer>
    <!-- ending for main content -->
    </div>
    <!-- ending for class wrapper -->
    </div>
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
    <style>
        .dashboard-content h1 {
            color: #2e5e4d;
            font-size: 28px;
            margin-bottom: 24px;
        }

        .dashboard-content h2 {
            color: #3a7356;
            font-size: 20px;
            margin-bottom: 16px;
            border-bottom: 2px solid #a9cdb6;
            padding-bottom: 6px;
        }

        /* ===== Household Info Section ===== */
        .household-information {
            background-color: #ffffff;
            border: 1px solid #d3e8db;
            border-left: 6px solid #4ca471;
            border-radius: 6px;
            padding: 20px 25px;
            margin-bottom: 24px;
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.03);
        }

        .household-information p {
            margin: 8px 0;
            font-size: 15px;
            line-height: 1.6;
        }

        /* ===== Buttons and Actions ===== */
        .household-actions,
        .resident-actions {
            margin-top: 16px;
            text-align: right;
        }

        .custom-cancel-button {
            background-color: #4ca471;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .custom-cancel-button:hover {
            background-color: #3e8d61;
        }

        /* ===== Family Card Styling ===== */
        .family-card {
            background-color: #f8fff8;
            border: 1px solid #cfe9d7;
            border-radius: 5px;
            padding: 15px 20px;
            margin-bottom: 16px;
        }

        .family-card strong {
            color: #2f5c47;
        }

        /* ===== Family Members List ===== */
        .family-members-list {
            padding-left: 20px;
            margin-top: 10px;
        }

        .family-members-list li {
            margin-bottom: 6px;
            font-size: 14.5px;
        }

        /* ===== Empty States ===== */
        .no-members,
        .no-families {
            color: #7f8c8d;
            font-style: italic;
            background-color: #fffef6;
            border-left: 4px solid #ffcd5d;
            padding: 8px 12px;
            margin-top: 12px;
            border-radius: 4px;
        }

    </style>
</body>
</html>
