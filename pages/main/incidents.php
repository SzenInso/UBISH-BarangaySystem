<?php
session_start();
include '../../config/dbfetch.php';
require '../../assets/libs/fpdf186/fpdf.php';

$errors = [];
$success = "";

// data based off of barangaye-blotter
if (isset($_POST['submit_incident'])) {
    $incidentType = $_POST['incident_type'];
    $incidentDate = $_POST['incident_date'];
    $place = $_POST['place_of_incident'];
    $reporter = $_POST['reporting_person'];
    $address = $_POST['home_address'];
    $narrative = $_POST['narrative'];
    $involved = $_POST['involved_parties'];
    $userId = $_SESSION['user_id'] ?? null;

    if (empty($incidentType) || empty($incidentDate) || empty($place) || empty($reporter) || empty($address) || empty($narrative) || empty($involved)) {
        $errors[] = "Please fill in all required fields.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO incidents (
                    incident_type, incident_date, place_of_incident, reporting_person,
                    home_address, narrative, involved_parties, submitted_by
                ) VALUES (
                    :incident_type, :incident_date, :place, :reporter,
                    :address, :narrative, :involved, :submitted_by
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':incident_type' => $incidentType,
            ':incident_date' => $incidentDate,
            ':place' => $place,
            ':reporter' => $reporter,
            ':address' => $address,
            ':narrative' => $narrative,
            ':involved' => $involved,
            ':submitted_by' => $userId
        ]);

        $issuedByQuery = "SELECT CONCAT(first_name, ' ', last_name) AS full_name, legislature FROM employee_details WHERE emp_id = :emp_id";
        $issuedByStmt = $pdo->prepare($issuedByQuery);
        $issuedByStmt->execute([":emp_id" => $_SESSION['emp_id']]);
        $issuedBy = $issuedByStmt->fetch();
        $fullName = $issuedBy['full_name'] ?? "N/A";
        $legislature = $issuedBy['legislature'] ?? "N/A";

        $success = "Incident successfully submitted.";

        // NOTE: This is a sample .pdf document. Official barangay document for the Incident Report is yet to be acquired.
        // generates pdf of incident report
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // brgy
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 10, 'Barangay Greenwater Village', 0, 1, 'C');
        $pdf->Ln(10);

        // title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Incident Report', 0, 1, 'C');
        $pdf->Ln(10);

        // details
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Incident Type:', 0, 0);
        $pdf->Cell(0, 10, $incidentType, 0, 1);

        $pdf->Cell(50, 10, 'Incident Date:', 0, 0);
        $pdf->Cell(0, 10, date('F j, Y', strtotime($incidentDate)), 0, 1);

        $pdf->Cell(50, 10, 'Place of Incident:', 0, 0);
        $pdf->Cell(0, 10, $place, 0, 1);

        $pdf->Cell(50, 10, 'Reporting Person:', 0, 0);
        $pdf->Cell(0, 10, $reporter, 0, 1);

        $pdf->Cell(50, 10, 'Home Address:', 0, 0);
        $pdf->MultiCell(0, 10, $address);

        $pdf->Cell(50, 10, 'Narrative of Incident:', 0, 0);
        $pdf->MultiCell(0, 10, $narrative);

        $pdf->Cell(50, 10, 'Involved Parties:', 0, 0);
        $pdf->MultiCell(0, 10, $involved);

        // issued by
        $pdf->Ln(20);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Issued by:', 0, 1);

        $pdf->Ln(10);
        $pdf->Cell(0, 1, "     " . $fullName, 0, 1);
        $pdf->Cell(0, 3, '_________________________', 0, 1);

        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 8, "     " . $legislature, 0, 1);

        $pdfFileName = 'Incident_Report_' . time() . '.pdf';
        $pdf->Output('D', $pdfFileName);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>UBISH Dashboard | Incident Report</title>
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
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/permits.php">Permit Requests</a></li>'; } ?>
                    <!-- STANDARD -->
                    
                    <!-- FULL -->
                    <?php if ($accessLevel >= 3) { echo '<li><a href="../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <!-- FULL -->
                    
                    <h3>Reports</h3>
                    <!-- STANDARD -->
                    <?php if ($accessLevel >= 2) { echo '<li class="active"><a href="../main/incidents.php">Incident Reports</a></li>'; }  ?>
                    <!-- STANDARD -->
                    
                    <li><a href="../main/incident_table.php">Incident History</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>

            <div class="dashboard-content">
                <h1>
                    <center>Incident Report Form</center>
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

                <style>
                    .incident-form {
                        max-width: 800px;
                        width: 100%;
                        background-color: #f8f9fa;
                        padding: 20px;
                        border-radius: 8px;
                        margin: 0 auto;
                    }

                    .incident-form label {
                        display: block;
                        font-size: 16px;
                        margin-bottom: 8px;
                        font-weight: 600;
                        color: #333;
                        text-align: left;
                    }

                    .incident-form input,
                    .incident-form textarea {
                        width: 100%;
                        padding: 10px;
                        margin-bottom: 20px;
                        border: 2px solid gray;
                        border-radius: 5px;
                        font-size: 14px;
                        color: #333;
                        background-color: #fff;
                        text-align: left;
                    }

                    .incident-form input:focus,
                    .incident-form textarea:focus {
                        outline: none;
                        border-color: #007bff;
                    }

                    .incident-form textarea {
                        resize: vertical;
                        min-height: 100px;
                    }

                    .incident-form button:focus {
                        outline: none;
                    }

                    .incident-form-container {
                        display: flex;
                        justify-content: space-between;
                        gap: 20px;
                    }

                    .incident-form-container label,
                    .incident-form-container input {
                        width: 48%;
                    }

                    .incident-form-container input[name="incident_type"] {
                        width: 100%;
                    }

                    .incident-form-container input[name="incident_date"] {
                        width: 100%;
                    }


                </style>

                <form method="POST" class="incident-form">

                    <div class="incident-form-container">
                        <label>Incident Type</label>
                        <input type="text" name="incident_type" required>
    
                        <label>Incident Date:</label>
                        <input type="date" name="incident_date" required>
                    </div>
                    

                    <label>Place of Incident:</label>
                    <input type="text" name="place_of_incident" required>

                    <label>Reporting Person:</label>
                    <input type="text" name="reporting_person" required>

                    <label>Home Address:</label>
                    <textarea name="home_address" required></textarea>

                    <label>Narrative of Incident:</label>
                    <textarea name="narrative" required></textarea>

                    <label>Involved Parties:</label>
                    <textarea name="involved_parties" required></textarea>

                    <button type="submit" name="submit_incident">Submit Incident</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>

</html>