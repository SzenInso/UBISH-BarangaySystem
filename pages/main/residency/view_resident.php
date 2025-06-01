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

    if (isset($_POST['resident_id'])) {
        $residentId = $_POST['resident_id'];
        $residentQuery = "
            SELECT 
                fm.*, 
                ha.house_number, ha.purok, ha.street, ha.district, ha.barangay
            FROM family_members AS fm
            JOIN families AS f ON fm.family_id = f.family_id
            JOIN households AS hh ON f.household_id = hh.household_id
            JOIN household_addresses AS ha ON hh.household_address_id = ha.household_address_id
            WHERE fm.member_id = :resident_id
        ";
        $stmt = $pdo->prepare($residentQuery);
        $stmt->execute([":resident_id" => $residentId]);
        $resident = $stmt->fetch();

        if (!$resident) {
            echo "<script>
                alert('Resident not found.');
                window.location.href = '../main/residency_management.php';
            </script>";
            exit;
        }
    } else {
        echo "<script>
            alert('No resident selected.');
            window.location.href = '../main/residency_management.php';
        </script>";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <title>UBISH Dashboard | View Resident</title>
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
        .resident-information {
            margin: 20px;
            padding: 30px;
            border: 2px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            font-size: 1.2rem;
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .resident-information h2 {
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: bold;
            color: #356859;
        }
        .resident-information .info-section {
            flex: 1;
        }
        .resident-information p {
            margin: 10px 0;
            font-size: 1.1rem;
        }
        .resident-name {
            text-align: center;
            margin: 20px 0;
        }
        .resident-name h2 {
            font-size: 3rem;
            font-weight: bold;
            color: #356859;
            margin: 0;
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
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/incidents.php">Incident Reports</a></li>'; }  ?>
                    <li><a href="../main/incident_table.php">Incident History</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <center><h1>Resident Information</h1></center>
                <div class="resident-name">
                    <?php
                    $name = htmlspecialchars($resident['last_name']);
                    if (!empty($resident['suffix'])) {
                        $name .= ' ' . htmlspecialchars($resident['suffix']); // Add suffix if available
                    }
                    $name .= ', ';
                    $name .= htmlspecialchars($resident['first_name']); // Add first name
                    if (!empty($resident['middle_initial'])) {
                        $middleInitial = strtoupper(substr($resident['middle_initial'], 0, 1)) . '.'; // Add middle initial if available
                        $name .= ' ' . htmlspecialchars($middleInitial);
                    }
                    ?>
                    <h2><?php echo $name; ?></h2>
                </div>
                <div class="resident-information">
                    <div class="info-section">
                        <p><strong>Sex:</strong> <?php echo ($resident['sex'] === 'M') ? 'Male' : 'Female'; ?></p>
                        <p><strong>Birthdate:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($resident['birthdate']))); ?></p>
                        <p><strong>Age:</strong> <?php echo date_diff(date_create($resident['birthdate']), date_create('today'))->y; ?></p>
                        <p><strong>Civil Status:</strong> <?php echo htmlspecialchars($resident['civil_status']); ?></p>
                        <p><strong>Religion:</strong> <?php echo htmlspecialchars($resident['religion'] ?? 'N/A'); ?></p>
                        <p><strong>Schooling:</strong> <?php echo htmlspecialchars($resident['schooling'] ?? 'N/A'); ?></p>
                        <p><strong>Attainment:</strong> <?php echo htmlspecialchars($resident['attainment'] ?? 'N/A'); ?></p>
                        <p><strong>Address:</strong> 
                            <?php 
                                $addressParts = [];
                                if (!empty($resident['house_number'])) { $addressParts[] = htmlspecialchars($resident['house_number']); }
                                if (!empty($resident['purok'])) { $addressParts[] = 'Purok ' . htmlspecialchars($resident['purok']); }
                                if (!empty($resident['street'])) { $addressParts[] = htmlspecialchars($resident['street']); }
                                if (!empty($resident['district'])) { $addressParts[] = 'District ' . htmlspecialchars($resident['district']); }
                                if (!empty($resident['barangay'])) { $addressParts[] = htmlspecialchars($resident['barangay']); }
                                echo implode(', ', $addressParts);
                            ?>
                        </p>
                    </div>

                    <div class="info-section">
                        <p><strong>Occupation:</strong> <?php echo htmlspecialchars($resident['occupation'] ?? 'N/A'); ?></p>
                        <p><strong>Employment Status:</strong> <?php echo htmlspecialchars($resident['emp_status'] ?? 'N/A'); ?></p>
                        <p><strong>Employment Category:</strong> <?php echo htmlspecialchars($resident['emp_category'] ?? 'N/A'); ?></p>
                        <p><strong>Income (Cash):</strong>&nbsp;₱&nbsp;<?php echo htmlspecialchars($resident['income_cash'] ?? 'N/A'); ?></p>
                        <p><strong>Income (Kind):</strong> <?php echo htmlspecialchars($resident['income_kind'] ?? 'N/A'); ?></p>
                        <p><strong>Livelihood Training:</strong> <?php echo htmlspecialchars($resident['livelihood_training'] ?? 'N/A'); ?></p>
                    </div>

                    <div class="info-section">
                        <p><strong>Senior Citizen:</strong> <?php echo ($resident['is_senior_citizen'] == 1) ? 'Yes' : 'No'; ?></p>
                        <p><strong>PWD:</strong> <?php echo ($resident['is_pwd'] == 1) ? 'Yes' : 'No'; ?></p>
                        <p><strong>OFW:</strong> <?php echo ($resident['is_ofw'] == 1) ? 'Yes' : 'No'; ?></p>
                        <p><strong>Solo Parent:</strong> <?php echo ($resident['is_solo_parent'] == 1) ? 'Yes' : 'No'; ?></p>
                        <p><strong>Indigenous:</strong> <?php echo ($resident['is_indigenous'] == 1) ? 'Yes' : 'No'; ?></p>
                    </div>
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
