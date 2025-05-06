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