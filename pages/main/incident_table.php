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
    <title>UBISH Dashboard | Incident History</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .incident-table-container {
            width: 100%;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
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
                        echo '<li><a href="#">Certificate Requests</a></li>';
                    } ?>
                    <?php if ($accessLevel >= 2) {
                        echo '<li><a href="#">Permit Requests</a></li>';
                    } ?>
                    <h3>Reports</h3>
                    <li><a href="../main/incidents.php">Incident Reports</a></li>
                    <li class="active"><a href="#">Incident History</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>

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
        </div>
    </main>

    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>

</body>

</html>