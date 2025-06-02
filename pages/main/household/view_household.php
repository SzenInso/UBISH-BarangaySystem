<?php
    include '../../../config/dbfetch.php';
    
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
    <title>UBISH Dashboard | View Household</title>
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
        .household-information {
            margin: 20px;
            padding: 30px;
            border: 2px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            font-size: 1.2rem;
            display: block;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }
        .household-information h2 {
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: bold;
            color: #356859;
        }
        .family-card {
            background: #fff;
            border: 1.5px solid #b7d6b7;
            border-radius: 8px;
            margin-bottom: 24px;
            padding: 18px 24px;
            box-shadow: 0 2px 8px rgba(53, 104, 89, 0.06);
        }
        .family-card strong {
            color: #356859;
        }
        .family-members-list {
            margin: 12px 0 0 0;
            padding-left: 24px;
        }
        .family-members-list li {
            margin-bottom: 6px;
            font-size: 1.08rem;
        }
        .no-members, .no-families {
            color: #888;
            font-style: italic;
            margin-top: 8px;
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
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../../main/incidents.php">Incident Reports</a></li>'; }  ?>
                    <li><a href="../../main/incident_table.php">Incident History</a></li>
                    <li><a href="../../main/reports.php">Analytics</a></li>
                </ul>
            </div>
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
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>
