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
    <link rel="stylesheet" href="css/dash.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Greenwater Village Dashboard | Change Password</title>
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
                </h2> </br>
                <h3><i class="fas fa-home"></i> Home</h3>
                <li class="active"><a href="../main/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../main/account.php"><i class="fas fa-user"></i> Account</a></li>
                <li><a href="../main/account_creation.php"><i class="fas fa-user-plus"></i> Account Creation</a></li>

                <h3><i class="fas fa-folder-open"></i> Documents & Disclosure</h3>
                <li><a href="../main/documents.php"><i class="fas fa-file-alt"></i> Documents</a></li>
                <li><a href="../main/announcements.php"><i class="fas fa-bullhorn"></i> Post Announcement</a></li>

                <h3><i class="fas fa-table"></i> Tables & Requests</h3>
                <li><a href="../main/employee_table.php"><i class="fas fa-users"></i> Employee Table</a></li>
                <li><a href="../main/account_requests.php"><i class="fas fa-user-check"></i> Account Requests</a></li>
                <li><a href="certificates/certificates.php"><i class="fas fa-certificate"></i> Certificate Requests</a></li>

                <h3><i class="fas fa-chart-bar"></i> Reports</h3>
                <li><a href="../main/incident_table.php"><i class="fas fa-exclamation-circle"></i> Incident History</a></li>
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
        </main>
            <footer class="main-footer">
                <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
            </footer>
        <!-- ending for main content -->
         </div>
    <!-- ending for class wrapper -->
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
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: #2e7d32; /* Forest green */
            text-align: center;
        }

        /* Change Password Form Table */
        .change-password-main table {
            width: 100%;
            border-spacing: 10px 15px;
            margin-bottom: 20px;
        }
        .change-password-main td {
            padding: 8px;
            vertical-align: middle;
            font-weight: 500;
            color: #33691e;
        }

        /* Input fields */
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #a5d6a7;
            border-radius: 6px;
            background-color: #f1f8e9;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
        }
        input[type="password"]:focus {
            outline: none;
            border-color: #66bb6a;
            background-color: #e8f5e9;
        }

        /* Action Buttons */
        .account-actions {
            text-align: center;
            margin-top: 25px;
        }
        #updateBtn,
        #changePwdBtn {
            background-color: #43a047;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            margin: 0 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        #changePwdBtn {
            background-color: #81c784;
        }
        #updateBtn:hover {
            background-color: #2e7d32;
        }
        #changePwdBtn:hover {
            background-color: #66bb6a;
        }

        /* Responsive layout */
        @media (max-width: 600px) {
            .change-password-main table,
            .change-password-main td {
                display: block;
                width: 100%;
            }
            .change-password-main td {
                margin-bottom: 10px;
            }
        }
    </style>
</body>
</html>