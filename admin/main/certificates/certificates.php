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
    <title>UBISH Dashboard | Certificate Requests</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>admin/main/certificates/certificates.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>admin/main/certificates/residency/residency_modal.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>admin/main/certificates/residency/residency_table.css">
</head>
<body>
<?php include '../partials/header.php'; ?>
<main>
    <div class="dashboard-main">
        <div class="dashboard-sidebar">
            <ul>
                <h3>Home</h3>
                <li class="active"><a href="<?= BASE_URL ?>admin/main/dashboard.php">Home</a></li>
                <li><a href="<?= BASE_URL ?>admin/main/account.php">Account</a></li>
                <li><a href="<?= BASE_URL ?>admin/main/account_creation.php">Account Creation</a></li>
                <h3>Documents & Disclosure</h3>
                <li><a href="<?= BASE_URL ?>admin/main/documents.php">Documents</a></li>
                <li><a href="<?= BASE_URL ?>admin/main/announcements.php">Post Announcement</a></li>
                <h3>Tables & Requests</h3>
                <li><a href="<?= BASE_URL ?>admin/main/employee_table.php">Employee Table</a></li>
                <li><a href="<?= BASE_URL ?>admin/main/account_requests.php">Account Requests</a></li>
                <li><a href="<?= BASE_URL ?>admin/main/certificates/certificates.php">Certificate Requests</a></li>
                <h3>Reports</h3>
                <li><a href="<?= BASE_URL ?>admin/main/incident_table.php">Incident History</a></li>
                <li><a href="<?= BASE_URL ?>admin/main/reports.php">Analytics</a></li>
            </ul>
        </div>
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
                    <div class="certificate-card">
                        <h3>Barangay Clearance</h3>
                        <a href="#?view=clearance">
                            <button class="dashboard-btn">View Requests</button>
                        </a>
                    </div>
                    <div class="certificate-card">
                        <h3>Good Moral Certificate</h3>
                        <a href="#?view=goodmoral">
                            <button class="dashboard-btn">View Requests</button>
                        </a>
                    </div>
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
    </div>
    
</main>
<?php include '../partials/footer.php'; ?>
</body>
</html>
