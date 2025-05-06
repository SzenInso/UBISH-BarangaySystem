<?php
include '../../config/dbfetch.php';

if (isset($_POST['confirm'])) {
    $currentPwd = (!empty($_POST['old_password'])) ? $_POST['old_password'] : null;
    $newPwd = (!empty($_POST['new_password'])) ? $_POST['new_password'] : null;
    $confirmPwd = (!empty($_POST['confirm_new_password'])) ? $_POST['confirm_new_password'] : null;

    if (empty($currentPwd) || empty($newPwd) || empty($confirmPwd)) {
        echo "
                <script>
                    alert('Please fill in all required fields. Note that a blank field is not allowed.');
                    window.location.href = '../main/change_password.php';
                </script>
            ";
        exit;
    } else if (!password_verify($currentPwd, $passwordHash)) {
        echo "
                <script>
                    alert('Current password is incorrect.');
                    window.location.href = '../main/change_password.php';
                </script>
            ";
    } elseif ($newPwd !== $confirmPwd) {
        echo "
                <script>
                    alert('New passwords do not match.');
                    window.location.href = '../main/change_password.php';
                </script>
            ";
    } else {
        $newPwdHash = password_hash($newPwd, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE login_details SET password = :password WHERE emp_id = :emp_id";
        $update = $pdo->prepare($updateQuery);
        $update->execute([
            ":password" => $newPwdHash,
            ":emp_id" => $_SESSION['emp_id']
        ]);

        if ($update) {
            echo "
                    <script>
                        alert('Password changed successfully.');
                        window.location.href = '../main/account.php';
                    </script>
                ";
        } else {
            echo "
                    <script>
                        alert('Failed to change password. Please try again.');
                        window.location.href = '../main/change_password.php';
                    </script>
                ";
        }
    }
}

if (isset($_POST['cancel'])) {
    header('location:../main/account.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>UBISH Dashboard | Change Password</title>
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
                    <li class="active"><a href="../main/account.php">Account</a></li>
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../main/documents.php">Documents</a></li>
                    <li><a href="../main/announcements.php">Post Announcement</a></li>
                    <h3>Tables & Requests</h3>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/employee_table.php">Employee Table</a></li>'; } ?>
                    <?php if ($accessLevel >= 3) { echo '<li><a href="../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="#">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="#">Permit Requests</a></li>'; } ?>
                    <h3>Reports</h3>
                    <li><a href="../main/incidents.php">Incident Reports</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1>
                    <center>Change Password</center>
                </h1><br>
                <form method="POST">
                    <div class="change-password-main">
                        <table>
                            <tr>
                                <td>Current Password:</td>
                                <td><input type="password" name="old_password" id="changePwdField"
                                        placeholder="Enter old password"></td>
                            </tr>
                            <tr>
                                <td>New Password:</td>
                                <td><input type="password" name="new_password" id="changePwdField"
                                        placeholder="Enter new password"></td>
                            </tr>
                            <tr>
                                <td>Confirm New Password:</td>
                                <td><input type="password" name="confirm_new_password" id="changePwdField"
                                        placeholder="Confirm new password"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="account-actions">
                        <button type="submit" name="confirm" id="updateBtn">Confirm Change Password</button>
                        <button type="submit" name="cancel" id="changePwdBtn">Cancel</button>
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