<?php
session_start();
include '../../config/dbfetch.php';

$errors = [];
$success = "";

// incidents fetch
$query = "SELECT * FROM incidents";
$stmt = $pdo->query($query);
$incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML STARTS HERE -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashPages.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Greenwater Village Dashboard | Incident History</title>
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
                            <img src="<?php echo $row['picture']; ?>" 
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
                            <img src="../../assets/img/GreenwaterLogo.jpg" alt="Greenwater Village Logo">
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
                    <h1>
                        <center>Incident Report History</center>
                    </h1><br>

                    <?php
                    if (!empty($errors)) {
                        foreach ($errors as $error) {
                            echo "<p style='color: red;'>$error</p>";
                        }
                    }

                    if (!empty($success)) {
                        echo "<p style='color: green;'>$success</p>";
                    }
                    ?>

                    <div class="incident-table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Incident Date</th>
                                    <th>Incident Type</th>
                                    <th>Place of Incident</th>
                                    <th>Reporting Person</th>
                                    <th>Home Address</th>
                                    <th>Narrative</th>
                                    <th>Involved Parties</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($incidents as $incident): ?>
                                    <tr>
                                        <td><?= htmlspecialchars(date('F j, Y', strtotime($incident['incident_date']))) ?></td>
                                        <td><?= htmlspecialchars($incident['incident_type']) ?></td>
                                        <td><?= htmlspecialchars($incident['place_of_incident']) ?></td>
                                        <td><?= htmlspecialchars($incident['reporting_person']) ?></td>
                                        <td><?= nl2br(htmlspecialchars($incident['home_address'])) ?></td>
                                        <td><?= nl2br(htmlspecialchars($incident['narrative'])) ?></td>
                                        <td><?= nl2br(htmlspecialchars($incident['involved_parties'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>

                <footer class="main-footer">
                    <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
                </footer>
        <!-- ending for the main content -->
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

        /* Heading */
        .dashboard-content h1 {
            font-size: 28px;
            color: #2e5e4d;
            margin-bottom: 20px;
        }

        /* Messages */
        .dashboard-content p[style*="color: red"] {
            background-color: #ffe6e6;
            color: #a94442;
            padding: 10px;
            border-left: 5px solid #d9534f;
            border-radius: 4px;
            margin: 10px 0;
        }

        .dashboard-content p[style*="color: green"] {
            background-color: #e1f4e3;
            color: #3c763d;
            padding: 10px;
            border-left: 5px solid #5cb85c;
            border-radius: 4px;
            margin: 10px 0;
        }

        /* Table container */
        .incident-table-container {
            overflow-x: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        /* Table styling */
        .incident-table-container table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            table-layout: fixed;
            word-wrap: break-word;
        }

        /* Table headers */
        .incident-table-container thead {
            background-color: #a6dcb9;
            color: #2e5e4d;
        }

        .incident-table-container th {
            text-align: left;
            padding: 12px 10px;
            font-weight: 600;
            border-bottom: 2px solid #8fc9a3;
        }

        /* Table rows */
        .incident-table-container td {
            padding: 12px 10px;
            vertical-align: top;
            border-bottom: 1px solid #e6e6e6;
            white-space: pre-line;
        }

        /* Zebra striping */
        .incident-table-container tbody tr:nth-child(even) {
            background-color: #f9fdfb;
        }

        /* Responsive text wrap for long content */
        .incident-table-container td,
        .incident-table-container th {
            word-break: break-word;
        }

        /* Hover effect */
        .incident-table-container tbody tr:hover {
            background-color: #edf9f0;
        }

    </style>
</body>
</html>