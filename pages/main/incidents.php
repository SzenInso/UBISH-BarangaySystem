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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../../assets/css/style.css"> -->
    <link rel="stylesheet" href="css/dashPages.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Greenwater Village Dashboard | Incident Report</title>
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
        </main>

            <footer class="main-footer">
                <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
            </footer>
        <!-- ending for the main content -->
         </div>
    <!-- ending for the class wrapper -->
    </div>
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
    <style>

/* Title */
.dashboard-content h1 {
    font-size: 28px;
    color: #2e5e4d; /* forest green */
    margin-bottom: 15px;
}

/* Error and success messages */
.dashboard-content p {
    font-size: 14px;
    margin: 8px 0;
}

.dashboard-content p[style*="color: red"] {
    background-color: #ffe6e6;
    padding: 8px 12px;
    border-left: 4px solid #d9534f;
    border-radius: 4px;
}

.dashboard-content p[style*="color: green"] {
    background-color: #e1f4e3;
    padding: 8px 12px;
    border-left: 4px solid #5cb85c;
    border-radius: 4px;
}

/* Form styling */
.incident-form {
    background-color: #ffffff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    max-width: 700px;
    margin: auto;
}

.incident-form-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
}

.incident-form-container label {
    width: 100%;
    font-weight: 600;
    color: #2e5e4d;
}

.incident-form-container input[type="text"],
.incident-form-container input[type="date"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #bbb;
    border-radius: 5px;
    background-color: #f7faf8;
}

/* Common inputs and textareas */
.incident-form label {
    display: block;
    margin-top: 15px;
    margin-bottom: 5px;
    font-weight: 600;
    color: #2e5e4d;
}

.incident-form input[type="text"],
.incident-form input[type="date"],
.incident-form textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #bbb;
    border-radius: 5px;
    font-size: 14px;
    background-color: #f7faf8;
    transition: border 0.3s;
}

.incident-form input:focus,
.incident-form textarea:focus {
    outline: none;
    border-color: #7cbf90; /* green outline on focus */
}

/* Submit button */
.incident-form button[type="submit"] {
    margin-top: 20px;
    background-color: #a6dcb9; /* light green */
    color: #2e5e4d;
    padding: 12px 20px;
    font-size: 15px;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.incident-form button[type="submit"]:hover {
    background-color: #8fd0a7;
}

    </style>
</body>

</html>