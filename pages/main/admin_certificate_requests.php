<?php
include '../../config/dbfetch.php';

// access level set to standard
$accessLevel = $_SESSION['access_level'] ?? 2;

// Pagination setup
$limit = 10;  // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Current page number
$offset = ($page - 1) * $limit;  // Offset for SQL query

// Fetch pending, approved, and rejected certificate requests with filters
$queryPending = "SELECT * FROM residency_requests WHERE status = 'Pending'";

$queryApproved = "SELECT * FROM residency_requests WHERE status = 'Approved'";

$queryRejected = "SELECT * FROM residency_requests WHERE status = 'Rejected'";

$queryPending .= " LIMIT :offset, :limit";
$queryApproved .= " LIMIT :offset, :limit";
$queryRejected .= " LIMIT :offset, :limit";

// Prepare the queries
$stmtPending = $pdo->prepare($queryPending);
$stmtApproved = $pdo->prepare($queryApproved);
$stmtRejected = $pdo->prepare($queryRejected);

// Bind parameters for pagination
$stmtPending->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtPending->bindValue(':limit', $limit, PDO::PARAM_INT);

$stmtApproved->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtApproved->bindValue(':limit', $limit, PDO::PARAM_INT);

$stmtRejected->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtRejected->bindValue(':limit', $limit, PDO::PARAM_INT);

$stmtPending->execute();
$stmtApproved->execute();
$stmtRejected->execute();

// Get total number of rows for pagination
$totalPending = $pdo->prepare("SELECT COUNT(*) FROM residency_requests WHERE status = 'Pending'");
$totalPending->execute();
$totalPendingRows = $totalPending->fetchColumn();
$totalPendingPages = ceil($totalPendingRows / $limit);

$totalApproved = $pdo->prepare("SELECT COUNT(*) FROM residency_requests WHERE status = 'Approved'");
$totalApproved->execute();
$totalApprovedRows = $totalApproved->fetchColumn();
$totalApprovedPages = ceil($totalApprovedRows / $limit);

$totalRejected = $pdo->prepare("SELECT COUNT(*) FROM residency_requests WHERE status = 'Rejected'");
$totalRejected->execute();
$totalRejectedRows = $totalRejected->fetchColumn();
$totalRejectedPages = ceil($totalRejectedRows / $limit);

// Handle approve/reject actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve'])) {
        // Approve request
        $id = $_POST['approve'];
        $updateQuery = "UPDATE residency_requests SET status = 'Approved' WHERE id = ?";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([$id]);
    } elseif (isset($_POST['reject'])) {
        // Reject request
        $id = $_POST['reject'];
        $updateQuery = "UPDATE residency_requests SET status = 'Rejected' WHERE id = ?";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([$id]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin_certificate_requests.css">
    <title>UBISH Dashboard | Certificate Requests</title>
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
                        echo '<li class="active"><a href="#">Certificate Requests</a></li>';
                    } ?>
                    <?php if ($accessLevel >= 2) {
                        echo '<li><a href="#">Permit Requests</a></li>';
                    } ?>
                    <h3>Reports</h3>
                    <li><a href="../main/incidents.php">Incident Reports</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>

            <div class="dashboard-content">
                <h1><center>Certificate Requests</center></h1><br>

                <!-- Pending Requests -->
                <h2>Pending Requests</h2>
                <?php
                if ($stmtPending->rowCount() > 0) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
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
                    echo "<th>Action</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    foreach ($stmtPending as $row) {
                        $full_name = htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['middle_name']) . ' ' . htmlspecialchars($row['last_name']);
                        $address = htmlspecialchars($row['street']) . ', ' . htmlspecialchars($row['barangay']) . ', ' . htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['province']) . ' ' . htmlspecialchars($row['zipcode']);
                        echo "<tr>";
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
                        echo "<td>
                                <form method='POST' style='display:inline;'>
                                    <button type='submit' name='approve' value='" . $row['id'] . "' class='btn btn-approve'>Approve</button>
                                    <button type='submit' name='reject' value='" . $row['id'] . "' class='btn btn-reject'>Reject</button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";

                    // Pagination Links for Pending
                    echo "<div class='pagination'>";
                    for ($i = 1; $i <= $totalPendingPages; $i++) {
                        echo "<a href='?page=$i'>$i</a> ";
                    }
                    echo "</div>";

                } else {
                    echo "<p>No pending requests found.</p>";
                }
                ?>

                <!-- Approved Requests -->
                <h2>Approved Requests</h2>
                <?php
                if ($stmtApproved->rowCount() > 0) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
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
                    echo "<th>Generate</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    foreach ($stmtApproved as $row) {
                        $full_name = htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['middle_name']) . ' ' . htmlspecialchars($row['last_name']);
                        $address = htmlspecialchars($row['street']) . ', ' . htmlspecialchars($row['barangay']) . ', ' . htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['province']) . ' ' . htmlspecialchars($row['zipcode']);
                        echo "<tr>";
                        echo "<td>" . $full_name . "</td>";
                        echo "<td>" . $address . "</td>";
                        echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['civil_status']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['citizenship']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['contact_number']) . "</td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['purpose'])) . "</td>";
                        echo "<td>" . date("F j, Y g:i A", strtotime($row['date_submitted'])) . "</td>";
                        echo "<td><span class='badge badge-success'>Approved</span></td>";
                        echo "<td>
                                <form method='POST' style='display:inline;'>
                                    <a href='generate_pdf.php?id=" . $row['id'] . "' target='_blank' class='btn btn-download'>Download PDF</a>
                                </form>
                            </td>";

                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";

                    // Pagination Links for Approved
                    echo "<div class='pagination'>";
                    for ($i = 1; $i <= $totalApprovedPages; $i++) {
                        echo "<a href='?page=$i'>$i</a> ";
                    }
                    echo "</div>";

                } else {
                    echo "<p>No approved requests found.</p>";
                }
                ?>

                <!-- Rejected Requests -->
                <h2>Rejected Requests</h2>
                <?php
                if ($stmtRejected->rowCount() > 0) {
                    echo "<table>";
                    echo "<thead>";
                    echo "<tr>";
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

                    foreach ($stmtRejected as $row) {
                        $full_name = htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['middle_name']) . ' ' . htmlspecialchars($row['last_name']);
                        $address = htmlspecialchars($row['street']) . ', ' . htmlspecialchars($row['barangay']) . ', ' . htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['province']) . ' ' . htmlspecialchars($row['zipcode']);
                        echo "<tr>";
                        echo "<td>" . $full_name . "</td>";
                        echo "<td>" . $address . "</td>";
                        echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['civil_status']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['citizenship']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['contact_number']) . "</td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['purpose'])) . "</td>";
                        echo "<td>" . date("F j, Y g:i A", strtotime($row['date_submitted'])) . "</td>";
                        echo "<td><span class='badge badge-danger'>Rejected</span></td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";

                    // Pagination Links for Rejected
                    echo "<div class='pagination'>";
                    for ($i = 1; $i <= $totalRejectedPages; $i++) {
                        echo "<a href='?page=$i'>$i</a> ";
                    }
                    echo "</div>";

                } else {
                    echo "<p>No rejected requests found.</p>";
                }
                ?>
            </div>
        </div>
    </main>
</body>

</html>
