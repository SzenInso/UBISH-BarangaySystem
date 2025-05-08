<?php
session_start();
include '../../config/dbfetch.php';

$errors = [];
$success = "";

// Sample access level (optional — same pattern as your file)
$accessLevel = $_SESSION['access_level'] ?? 0;

// Fetch pending certificate requests
$query = "SELECT * FROM residency_requests WHERE status = 'Pending'";
$stmt = $pdo->prepare($query);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>UBISH Dashboard | Certificate Requests</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        button {
            border: 2px solid gray;
            background-color: white;
            color: black;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        button:hover {
            background-color: lightgray;
        }

        button:focus {
            outline: none;
        }

        button.logout {
            border: none;
            background-color: white;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 2px solid gray;
        }

        th, td {
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            font-size: 12px;
        }

        .badge-warning {
            background-color: orange;
        }
    </style>
</head>

<body>
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
                    <?php if ($accessLevel >= 2) {
                        echo '<li><a href="../main/employee_table.php">Employee Table</a></li>';
                    } ?>
                    <?php if ($accessLevel >= 3) {
                        echo '<li><a href="../main/account_requests.php">Account Requests</a></li>';
                    } ?>
                    <?php if ($accessLevel >= 2) {
                        echo '<li class="active"><a href="../main/admin_certificate_requests.php">Certificate Requests</a></li>';
                    } ?>
                    <?php if ($accessLevel >= 2) {
                        echo '<li><a href="#">Permit Requests</a></li>';
                    } ?>
                    <h3>Reports</h3>
                    <li><a href="#">Incident Reports</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>

            <div class="dashboard-content">
                <h1>
                    <center>Pending Residency Certificate Requests</center>
                </h1><br>

                <?php
                if ($stmt->rowCount() > 0) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>ID</th>";
                    echo "<th>Resident Name</th>";
                    echo "<th>Address</th>";
                    echo "<th>Age</th>";
                    echo "<th>Civil Status</th>";
                    echo "<th>Citizenship</th>";
                    echo "<th>Email</th>";
                    echo "<th>Contact #</th>";
                    echo "<th>Purpose</th>";
                    echo "<th>Date Submitted</th>";
                    echo "<th>Status</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    foreach ($stmt as $row) {
                        $full_name = htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['middle_name']) . ' ' . htmlspecialchars($row['last_name']);
                        $address = htmlspecialchars($row['street']) . ', ' . htmlspecialchars($row['barangay']) . ', ' . htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['province']) . ' ' . htmlspecialchars($row['zipcode']);
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . $full_name . "</td>";
                        echo "<td>" . $address . "</td>";
                        echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['civil_status']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['citizenship']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['contact_number']) . "</td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['purpose'])) . "</td>";
                        echo "<td>" . date("F j, Y g:i A", strtotime($row['date_submitted'])) . "</td>";
                        echo "<td><span class='badge badge-warning'>Pending</span></td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p style='color: gray; text-align: center;'>No pending residency certificate requests.</p>";
                }
                ?>
            </div>
        </div>
    </main>

    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>

</html>
