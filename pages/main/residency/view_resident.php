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
            echo "
                <link rel='stylesheet' href='../../../assets/css/style.css'>
                <script src='js/sweetalert2.js'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Resident not found.',
                            icon: 'info',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href='../residency_management.php';
                        });
                    });
                </script>
            ";
            exit;
        }
    } else {
        echo "
            <link rel='stylesheet' href='../../../assets/css/style.css'>
            <script src='js/sweetalert2.js'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'No resident selected.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href='../residency_management.php';
                    });
                });
            </script>
        ";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashPages.css">
    <link rel="icon" type="image/x-icon" href="../../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Greenwater Village Dashboard | View Resident</title>
</head>
<body>
    <style>
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
                <li class="active"><a href="../../main/dashboard.php"><i class="fas fa-tachometer-alt"></i> Home</a></li>
                <li><a href="../../main/account.php"><i class="fas fa-user"></i> Account</a></li>

                <h3><i class="fas fa-folder-open"></i> Documents & Disclosure</h3>
                <li><a href="../../main/documents.php"><i class="fas fa-file-alt"></i> Documents</a></li>
                <li><a href="../../main/announcements.php"><i class="fas fa-bullhorn"></i> Post Announcement</a></li>

                <h3><i class="fas fa-table"></i> Tables & Requests</h3>
                <li><a href="../../main/employee_table.php"><i class="fas fa-users"></i> Employee Table</a></li>

                <!-- STANDARD ACCESS LEVEL -->
                <?php if ($accessLevel >= 2): ?>
                    <li><a href="../../main/residency_management.php"><i class="fas fa-house-user"></i> Residency Management</a></li>
                    <!-- <li><a href="../main/certificates.php"><i class="fas fa-certificate"></i> Certificate Requests</a></li> -->
                    <!-- <li><a href="../main/permits.php"><i class="fas fa-id-badge"></i> Permit Requests</a></li> -->
                <?php endif; ?>

                <!-- FULL ACCESS LEVEL -->
                <?php if ($accessLevel >= 3): ?>
                    <li><a href=".././/main/account_requests.php"><i class="fas fa-user-check"></i> Account Requests</a></li>
                <?php endif; ?>

                <h3><i class="fas fa-chart-bar"></i> Reports</h3>
                <?php if ($accessLevel >= 2): ?>
                    <li><a href="../../main/incidents.php"><i class="fas fa-exclamation-circle"></i> Incident Reports</a></li>
                <?php endif; ?>
                <li><a href="../../main/incident_table.php"><i class="fas fa-history"></i> Incident History</a></li>
                <li><a href="../../main/reports.php"><i class="fas fa-chart-line"></i> Analytics</a></li>
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
                        <center><button class="custom-cancel-button" name="go-back">Go Back</button></center>
                    </form>
                </div>
            </div>
    </main>
    <footer class="main-footer">
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
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
