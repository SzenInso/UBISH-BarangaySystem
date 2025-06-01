<?php
    include '../../config/dbfetch.php';
    
    // access level verification
    if (!isset($_SESSION['user_id']) || $accessLevel < 2) {
        header("Location: ../main/dashboard.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>UBISH Dashboard | Residency Management</title>
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
        .custom-cancel-button {
            border: 2px solid gray;
            background-color: white;
            color: black;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .custom-cancel-button:hover {
            background-color: lightgray;
        }
        .residency-actions {
            display: flex;
            gap: 8px;
        }

        .residency-actions form {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            border: 2px solid gray;
        }

        table td {
            border: 2px solid gray;
        }

        .info-box {
            background: #e6f7e6;
            border: 1px solid #356859;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 20px;
            color: #2b3d2f;
            max-width: 1024px;
            min-width: 0;
            display: block;
            box-sizing: border-box;
            margin: 24px auto 20px auto;
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
                    <li><a href="../main/account.php">Account</a></li>
                    
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../main/documents.php">Documents</a></li>
                    <li><a href="../main/announcements.php">Post Announcement</a></li>
                    
                    <h3>Tables & Requests</h3>
                    <li><a href="../main/employee_table.php">Employee Table</a></li>

                    <!-- STANDARD -->
                    <?php if ($accessLevel >= 2) { echo '<li class="active"><a href="../main/residency_management.php">Residency Management</a></li>'; } ?>
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
                <center><h1>Residency Management</h1></center>
                <?php 
                    $residencyQuery = "SELECT * FROM family_members ORDER BY last_name, first_name";
                    $residencyStmt = $pdo->query($residencyQuery);
                    $residency = $residencyStmt->fetchAll();
                ?>
                <br>
                <div class="residency-table-actions">
                    <label for="view-by">View By:</label>
                    <select id="residency-view">
                        <option value="residency">Residency</option>
                        <option value="household">Household</option>
                    </select>
                </div>
                <div id="ajax-table-container">
                    <!-- Table will be loaded here -->
                </div>
                <script>
                    function loadTable(type) {
                        var xhr = new XMLHttpRequest();
                        var url = (type === 'household') ? 'household_table.php' : 'residency_table.php';
                        xhr.open('GET', url, true);
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                document.getElementById('ajax-table-container').innerHTML = xhr.responseText;
                            }
                        };
                        xhr.send();
                    }
                    document.getElementById('residency-view').addEventListener('change', function() {
                        loadTable(this.value);
                    });
                    window.onload = function() {
                        loadTable(document.getElementById('residency-view').value);
                    };
                </script>
            </div>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>
