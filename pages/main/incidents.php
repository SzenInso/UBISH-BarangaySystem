<?php
session_start();
include '../../config/dbfetch.php';

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

        $success = "Incident successfully submitted.";
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
                    <li class="active"><a href="#">Incident Reports</a></li>
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

                <form method="POST" class="incident-form">
                    <label>Incident Type:
                        <input type="text" name="incident_type" required>
                    </label><br><br>

                    <label>Incident Date:
                        <input type="date" name="incident_date" required>
                    </label><br><br>

                    <label>Place of Incident:
                        <input type="text" name="place_of_incident" required>
                    </label><br><br>

                    <label>Reporting Person:
                        <input type="text" name="reporting_person" required>
                    </label><br><br>

                    <label>Home Address:
                        <textarea name="home_address" required></textarea>
                    </label><br><br>

                    <label>Narrative of Incident:
                        <textarea name="narrative" required></textarea>
                    </label><br><br>

                    <label>Involved Parties:
                        <textarea name="involved_parties" required></textarea>
                    </label><br><br>

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