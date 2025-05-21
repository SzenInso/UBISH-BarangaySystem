<?php
session_start();
include '../../config/dbfetch.php';
require '../../assets/libs/fpdf186/fpdf.php';

$errors = [];
$success = "";

// data is bassed off barangay permit from Roxas City, Capiz
if (isset($_POST['submit_permit'])) {
    $name = $_POST['name'];
    $event = $_POST['event'];
    $eventDate = $_POST['event_date'];
    $eventTime = $_POST['event_time'];
    $venue = $_POST['venue'];
    $userId = $_SESSION['user_id'] ?? null;

    if (empty($name) || empty($event) || empty($eventDate) || empty($eventTime) || empty($venue)) {
        $errors[] = "Please fill in all required fields.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO permits (
                    name, event, event_date, event_time, venue, issued_by
                ) VALUES (
                    :name, :event, :event_date, :event_time, :venue, :issued_by
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':event' => $event,
            ':event_date' => $eventDate,
            ':event_time' => $eventTime,
            ':venue' => $venue,
            ':issued_by' => $userId
        ]);

        $success = "Barangay Permit successfully submitted.";

        // fetch issuer info
        $issuedByQuery = "SELECT CONCAT(first_name, ' ', last_name) AS full_name FROM employee_details WHERE emp_id = :emp_id";
        $issuedByStmt = $pdo->prepare($issuedByQuery);
        $issuedByStmt->execute([":emp_id" => $_SESSION['emp_id']]);
        $issuer = $issuedByStmt->fetch();
        $fullName = $issuer['full_name'] ?? "N/A";

        // generate pdf using fpdf
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Barangay Greenwater Village', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Barangay Permit', 0, 1, 'C');
        $pdf->Ln(15);

        $pdf->SetFont('Arial', '', 12);
        $formattedDate = date('F j, Y', strtotime($eventDate));
        $currentDate = date('F j, Y');

        $content = "TO WHOM IT MAY CONCERN:\n\n" .
            "This is to certify that " . strtoupper($name) . ", a resident of Barangay Greenwater Village, Baguio City, " .
            "has requested permission to hold a \"" . $event . "\", with the following details:\n\n" .
            "Date: " . $formattedDate . "\n" .
            "Time: " . $eventTime . " onward\n" .
            "Venue: " . strtoupper($venue) . "\n\n" .
            "This certification is being issued upon the request of the said interested party together with the assistant " .
            "of Police men, Barangay Council, and Barangay Tanod.\n\n" .
            "Issued this " . $currentDate . " at Barangay Greenwater Village, Baguio City.\n\n\n" .
            "__________________________\n" .
            "Punong Barangay";

        $pdf->MultiCell(0, 10, $content);
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, "Issued by: " . $fullName, 0, 1, 'C');

        $pdfFileName = 'Barangay_Permit_' . time() . '.pdf';
        $pdf->Output('D', $pdfFileName);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>UBISH Dashboard | Barangay Permit</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        button {
            border: 2px solid gray;
            background-color: white;
            color: black;
            padding: 8px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: lightgray;
        }

        button.logout {
            border: none;
            background-color: white;
            font-size: 16px;
        }

        .permit-form,
        .permit-form label {
            text-align: left;
            margin: 0;
            width: 100%;
            box-sizing: border-box;
        }

        .permit-form input,
        .permit-form textarea {
            margin-bottom: 16px;
            width: 100%;
            padding: 8px;
            padding-left: 0px;
            box-sizing: border-box;
        }
        .dashboard-content {
            width: 48%;
            background-color: #f8f9fa
        }
    </style>
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
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/certificates.php">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li class="active"><a href="../main/permits.php">Permit Requests</a></li>'; } ?>
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

            <center>
            <div class="dashboard-content">
                <h1>
                    <center>Barangay Permit Form</center>
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

                <form method="POST" class="permit-form">
                    <label><h4>Full Name of Requestor</h4></label>
                    <input type="text" name="name" required>

                    <label><h4>Event Name</h4></label>
                    <input type="text" name="event" required>

                    <label><h4>Date of Event</h4></label>
                    <input type="date" name="event_date" required>

                    <label><h4>Time</h4></label>
                    <input type="text" name="event_time" required placeholder="e.g., 6:00 PM">

                    <label><h4>Venue</h4></label>
                    <input type="text" name="venue" required><br/>

                    <button type="submit" name="submit_permit">Submit Permit</button>
                </form>
            </div>
        </div>
        </center>
    </main>

    <footer>
        <hr>
        <p>&copy; <?php echo date('Y'); ?> | Unified Barangay Information Service Hub</p>
    </footer>
</body>

</html>