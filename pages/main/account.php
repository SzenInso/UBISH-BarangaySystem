<?php
include '../../config/dbfetch.php';

if (isset($_POST['update'])) {
    header('location:../main/account_update.php');
    exit;
}

if (isset($_POST['change-password'])) {
    header('location:../main/change_password.php');
    exit;
}

if (isset($_POST['reset-question'])) {
    header('location:../main/security_question.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>UBISH Dashboard | Account</title>
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
                    <li class="active"><a href="../main/account.php">Account</a></li>
                    
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
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/incidents.php">Incident Reports</a></li>'; }  ?>
                    <!-- STANDARD -->
                    
                    <li><a href="../main/incident_table.php">Incident History</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1>
                    <center>Account Page</center>
                </h1><br>
                <?php
                foreach ($empDetails as $row) {
                    ?>
                    <img id="employee-picture" src="<?php echo $row['picture']; ?>" alt="Employee Picture"
                        title="<?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']; ?>">
                    <style>
                        .account-main {
                            display: flex;
                            justify-content: space-between;
                        }

                        .employee-details,
                        .account-details {
                            margin: 0 16px;
                        }
                    </style>
                    <div class="account-main">
                        <div class="employee-details">
                            <table>
                                <tr>
                                    <td colspan="2">
                                        <h2>Employee Details</h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>UBISH Employee ID: </strong></td>
                                    <td><?php echo $row['emp_id']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Full Name: </strong></td>
                                    <td><?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Date of Birth: </strong></td>
                                    <td><?php echo date('F j, Y', strtotime($row['date_of_birth'])); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Biological Sex: </strong></td>
                                    <td>
                                        <?php
                                        if ($row['sex'] === 'M')
                                            echo "Male";
                                        elseif ($row['sex'] === 'F')
                                            echo "Female";
                                        elseif ($row['sex'] === 'I')
                                            echo "Intersex";
                                        else
                                            echo "Not Specified";
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Age: </strong></td>
                                    <td>
                                        <?php
                                        $birthDate = new DateTime($row['date_of_birth']);
                                        $today = new DateTime('today');
                                        $age = $birthDate->diff($today)->y;
                                        echo $age;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Address: </strong></td>
                                    <td><?php echo $row['address'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Religion: </strong></td>
                                    <td><?php echo $row['religion'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Civil Status: </strong></td>
                                    <td><?php echo $row['civil_status'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Phone Number: </strong></td>
                                    <td><?php echo $row['phone_no'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Legislature: </strong></td>
                                    <td><?php echo $row['legislature'] ?></td>
                                </tr>
                                <?php if (!empty($row['committee'])) { ?>
                                    <tr>
                                        <td><strong>Committee: </strong></td>
                                        <td><?php echo $row['committee']; ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                        <div class="account-details">
                            <table>
                                <tr>
                                    <td colspan="2">
                                        <h2>Account Details</h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Username: </strong></td>
                                    <td><?php echo $row['username']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email: </strong></td>
                                    <td><?php echo $row['email']; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><strong>Security Question: </strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <?php
                                            $secQuestion = $securityQuestion->fetchColumn();
                                            if (empty($secQuestion)) {
                                                echo "None";
                                            } else {
                                                echo htmlspecialchars($secQuestion);
                                            }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <?php } ?>
                <form method="POST">
                    <div class="account-actions">
                        <button type="submit" name="update" class="update-btn" id="updateBtn">Update Account</button>
                        <button type="submit" name="change-password" class="change-password" id="changePwdBtn">
                            Change Password</button>
                        <button type="submit" name="reset-question" class="reset-question" id="resetQuestion">
                            Security Question Settings</button>
                    </div>
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