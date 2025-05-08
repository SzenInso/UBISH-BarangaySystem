<?php
session_start();
include '../../config/dbfetch.php';
require '../../assets/libs/fpdf186/fpdf.php';

$errors = [];
$success = "";

// data based off of the certificate of residency from (morepower.com.ph/wp-content/uploads/2020/05/Barangay-Certificate-of-Residency.pdf)
if (isset($_POST['submit_certificate'])) {
    $applicantName = $_POST['applicant_name'];
    $address = $_POST['address'];
    $propertyOwner = $_POST['property_owner'];
    $userId = $_SESSION['user_id'] ?? null;

    if (empty($applicantName) || empty($address) || empty($propertyOwner)) {
        $errors[] = "Please fill in all required fields.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO certificates (
                    applicant_name, address, property_owner, issued_by
                ) VALUES (
                    :applicant_name, :address, :property_owner, :issued_by
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':applicant_name' => $applicantName,
            ':address' => $address,
            ':property_owner' => $propertyOwner,
            ':issued_by' => $userId
        ]);

        $success = "Certificate of Residency successfully submitted.";

        // fetch issuer info
        $issuedByQuery = "SELECT CONCAT(first_name, ' ', last_name) AS full_name, legislature FROM employee_details WHERE emp_id = :emp_id";
        $issuedByStmt = $pdo->prepare($issuedByQuery);
        $issuedByStmt->execute([":emp_id" => $_SESSION['emp_id']]);
        $issuedBy = $issuedByStmt->fetch();
        $fullName = $issuedBy['full_name'] ?? "N/A";
        $legislature = $issuedBy['legislature'] ?? "N/A";

        // generate pdf using fpdf
        $pdf = new FPDF();
        $pdf->AddPage();


        // DOCUMENT CONTENT STARTS HERE
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Barangay Greenwater Village', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Certificate of Residency', 0, 1, 'C');
        $pdf->Ln(10);


        $currentDate = date('jS \of F, Y');

        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, "This is to certify that " . strtoupper($applicantName) . ",\n
        of legal age, Filipino citizen, is the present occupant of the address: " . strtoupper($address) . ", Greenwater Village, Baguio City, \n
        which property is owned by " . strtoupper($propertyOwner) . ".\n\n
        Based on records of this office, the above-named individual has no derogatory or criminal records filed in this barangay.\n\n
        This certification is being issued upon the request of the above-named person\n
        intended for compliance with the requirements of the issuing barangay.\n
        Issued this $currentDate, by Barangay Greenwater Village, Baguio City.\n
        ______________________________\n
        Punong Barangay\n");

        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, "Issued by: " . $fullName, 0, 1, 'C');

        // pdf save
        $pdfFileName = 'Certificate_of_Residency_' . time() . '.pdf';
        $pdf->Output('D', $pdfFileName);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>UBISH Dashboard | Certificate of Residency</title>
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

        .certificate-form,
        .certificate-form label {
            text-align: left;
            margin: 0;
            width: 100%;
            box-sizing: border-box;
        }

        .certificate-form input,
        .certificate-form textarea {
            margin-bottom: 16px;
            width: 100%;
            padding: 8px;
            padding-left: 0px;
            box-sizing: border-box;
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
                        echo '<li  class="active"><a href="../main/certificates.php">Certificate Requests</a></li>';
                    } ?>
                    <?php if ($accessLevel >= 2) {
                        echo '<li><a href="../main/permits.php">Permit Requests</a></li>';
                    } ?>
                    <h3>Reports</h3>
                    <li><a href="#">Incident Reports</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>

            <div class="dashboard-content">
                <h1>
                    <center>Certificate of Residency Form</center>
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

                <form method="POST" class="certificate-form">
                    <label><h4>Applicant Name</h4></label>
                    <input type="text" name="applicant_name" required>

                    <label><h4>Address</h4></label>
                    <input type="text" name="address" required>

                    <label><h4>Property Owner</h4></label>
                    <input type="text" name="property_owner" required><br />

                    <button type="submit" name="submit_certificate">Submit Certificate</button>
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