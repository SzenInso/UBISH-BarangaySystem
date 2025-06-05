<?php
session_start();
include '../../../config/dbfetch.php';
include '../../../baseURL.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>admin/main/certificates/certificates.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>admin/main/certificates/residency/residency_modal.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>admin/main/certificates/residency/residency_table.css">
    <link rel="stylesheet" href="../css/dash.css">
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Greenwater Village Dashboard | Certificate Requests</title>
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
                            <img src="../<?php echo $row['picture']; ?>" 
                                class="avatar img-fluid rounded-circle me-2" 
                                alt="<?php echo $row['first_name']; ?>" 
                                width="70" height="70">
                        </div>
                        <span class="text-dark fw-semibold"><?php echo $row['first_name']; ?></span>
                        </center>
                    </div>
                </h2> </br>
                <h3><i class="fas fa-home"></i> Home</h3>
                <li class="active"><a href="../../main/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../../main/account.php"><i class="fas fa-user"></i> Account</a></li>
                <li><a href="../../main/account_creation.php"><i class="fas fa-user-plus"></i> Account Creation</a></li>

                <h3><i class="fas fa-folder-open"></i> Documents & Disclosure</h3>
                <li><a href="../main/../documents.php"><i class="fas fa-file-alt"></i> Documents</a></li>
                <li><a href="../main/../announcements.php"><i class="fas fa-bullhorn"></i> Post Announcement</a></li>

                <h3><i class="fas fa-table"></i> Tables & Requests</h3>
                <li><a href="../../main/employee_table.php"><i class="fas fa-users"></i> Employee Table</a></li>
                <li><a href="../../main/account_requests.php"><i class="fas fa-user-check"></i> Account Requests</a></li>
                <li><a href="certificates/certificates.php"><i class="fas fa-certificate"></i> Certificate Requests</a></li>

                <h3><i class="fas fa-chart-bar"></i> Reports</h3>
                <li><a href="../../main/incident_table.php"><i class="fas fa-exclamation-circle"></i> Incident History</a></li>
                <li><a href="../../main/reports.php"><i class="fas fa-chart-line"></i> Analytics</a></li>
            </ul>
        </aside>
    
    <div class="main-content">
            <header class="main-header">
                <button class="hamburger" id="toggleSidebar">&#9776;</button>
                <div class="header-container">
                    <div class="logo">
                        <img src="<?= BASE_URL ?>assets/img/GreenwaterLogo.jpg" alt="Greenwater Village Logo">
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
                <div class="certificate-container">
                    <h1>Certificate Requests</h1>
                    <p>Click on a certificate type below to manage and review requests.</p>
                    <div class="certificate-options">
                        <div class="certificate-card">
                            <h3>Certificate of Residency</h3>
                            <a href="certificates.php?view=residency">
                                <button class="dashboard-btn">View Requests</button>
                            </a>
                        </div>
                        <!-- <div class="certificate-card">
                            <h3>Barangay Clearance</h3>
                            <a href="#?view=clearance">
                                <button class="dashboard-btn">View Requests</button>
                            </a>
                        </div> -->
                        <!-- <div class="certificate-card">
                            <h3>Good Moral Certificate</h3>
                            <a href="#?view=goodmoral">
                                <button class="dashboard-btn">View Requests</button>
                            </a>
                        </div> -->
                    </div>
                </div>

                <?php
                if (isset($_GET['view'])) {
                    $view = $_GET['view'];
                    if ($view === 'residency') {
                        include 'residency/residency_table.php';
                    } elseif ($view === 'clearance') {
                        include 'clearance_table.php';
                    } elseif ($view === 'goodmoral') {
                        include 'goodmoral_table.php';
                    }
                }
                ?>
            </div>
    </main>
    <?php include 'residency/residencyEditModal.php';?>
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
</body>
</html>
