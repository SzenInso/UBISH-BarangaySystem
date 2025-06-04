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
    <link rel="stylesheet" href="css/dash.css">
    <link rel="stylesheet" href="css/account.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Greenwater Village Dashboard | Account</title>

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
                    <center>Account Page</center>
                </h1><br>
                <?php
                foreach ($empDetails as $row) {
                    ?>
                    <img id="employee-picture" src="<?php echo $row['picture']; ?>" alt="Employee Picture"
                        title="<?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']; ?>">

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
    </main>
            <footer class="main-footer">
                <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
            </footer>
   </div> 
</div>

    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>