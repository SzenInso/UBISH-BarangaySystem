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
    <link rel="stylesheet" href="css/dashPages.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Greenwater Village Dashboard | Residency Management</title>
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
                </h2>

                <h3><i class="fas fa-home"></i> Home</h3>
                <li class="active"><a href="../main/dashboard.php"><i class="fas fa-tachometer-alt"></i> Home</a></li>
                <li><a href="../main/account.php"><i class="fas fa-user"></i> Account</a></li>

                <h3><i class="fas fa-folder-open"></i> Documents & Disclosure</h3>
                <li><a href="../main/documents.php"><i class="fas fa-file-alt"></i> Documents</a></li>
                <li><a href="../main/announcements.php"><i class="fas fa-bullhorn"></i> Post Announcement</a></li>

                <h3><i class="fas fa-table"></i> Tables & Requests</h3>
                <li><a href="../main/employee_table.php"><i class="fas fa-users"></i> Employee Table</a></li>

                <!-- STANDARD ACCESS LEVEL -->
                <?php if ($accessLevel >= 2): ?>
                    <li><a href="../main/residency_management.php"><i class="fas fa-house-user"></i> Residency Management</a></li>
                    <!-- <li><a href="../main/certificates.php"><i class="fas fa-certificate"></i> Certificate Requests</a></li> -->
                    <!-- <li><a href="../main/permits.php"><i class="fas fa-id-badge"></i> Permit Requests</a></li> -->
                <?php endif; ?>

                <!-- FULL ACCESS LEVEL -->
                <?php if ($accessLevel >= 3): ?>
                    <li><a href="../main/account_requests.php"><i class="fas fa-user-check"></i> Account Requests</a></li>
                <?php endif; ?>

                <h3><i class="fas fa-chart-bar"></i> Reports</h3>
                <?php if ($accessLevel >= 2): ?>
                    <li><a href="../main/incidents.php"><i class="fas fa-exclamation-circle"></i> Incident Reports</a></li>
                <?php endif; ?>
                <li><a href="../main/incident_table.php"><i class="fas fa-history"></i> Incident History</a></li>
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

            <main>
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
                        <select id="residency-view" style="width: 200px; padding: 8px; border-radius: 6px; border: 2px solid #356859; font-size: medium; background: #f8fff8;">
                            <option value="residency">Residency</option>
                            <option value="household">Household</option>
                        </select>
                        <input type="text" id="search-bar" class="styled-search-bar" placeholder="Search by name...">
                    </div>
                    <div id="ajax-table-container">
                        <!-- Table will be loaded here -->
                    </div>
                    <div id="table-loader" style="display:none; text-align:center; margin: 24px 0;">
                        <span style="display:inline-block; width:40px; height:40px; border:4px solid #b0b0b0; border-top:4px solid #356859; border-radius:50%; animation: spin 1s linear infinite;"></span>
                        <div style="margin-top:8px; color:#888;">Loading...</div>
                    </div>
                    <script>
                        function loadTable(type) {
                            document.getElementById('table-loader').style.display = 'block';
                            document.getElementById('ajax-table-container').innerHTML = '';
                            var xhr = new XMLHttpRequest();
                            var url = (type === 'household') ? 'household_table.php' : 'residency_table.php';
                            xhr.open('GET', url, true);
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === 4) {
                                    document.getElementById('table-loader').style.display = 'none';
                                    if (xhr.status === 200) {
                                        document.getElementById('ajax-table-container').innerHTML = xhr.responseText;
                                        reapplySearch();
                                    }
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
                        document.getElementById('search-bar').addEventListener('input', function() {
                            const searchTerm = this.value.toLowerCase();
                            // Wait for table to be loaded
                            const table = document.querySelector('#ajax-table-container table');
                            if (!table) return;
                            const rows = table.querySelectorAll('tbody tr');
                            rows.forEach(row => {
                                const text = row.textContent.toLowerCase();
                                row.style.display = text.includes(searchTerm) ? '' : 'none';
                            });
                        });

                        // Re-apply search after AJAX table reload
                        function reapplySearch() {
                            const event = new Event('input');
                            document.getElementById('search-bar').dispatchEvent(event);
                        }
                    </script>
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

        .styled-search-bar {
            width: 350px;
            padding: 10px 16px;
            border-radius: 6px;
            border: 2px solid #356859;
            font-size: 1.1rem;
            margin-left: 24px;
            background: #f8fff8;
            transition: border-color 0.2s;
        }
        .styled-search-bar:focus {
            outline: none;
            border-color: #37966f;
            background: #e6f7e6;
        }

        /* loading */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
     /* Page Title */
        .dashboard-content h1 {
            font-size: 26px;
            color: #2e5e4d;
            margin-bottom: 20px;
        }
        /* Search Bar */
        .styled-search-bar {
            flex: 1;
            max-width: 300px;
            padding: 8px 12px;
            border: 2px solid #b0c4b1;
            border-radius: 6px;
            background-color: #ffffff;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        .styled-search-bar:focus {
            border-color: #66bb6a;
            outline: none;
        }


        /* Table Header */
        #ajax-table-container th {
            background-color: #d7f5e7;
            color: #356859;
            text-align: left;
            padding: 12px;
        }

        /* Table Rows */
        #ajax-table-container td {
            padding: 12px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #333;
        }

        #ajax-table-container tr:hover {
            background-color: #f0fdf3;
        }

        /* Loader Spinner */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #table-loader span {
            border: 4px solid #b0b0b0;
            border-top: 4px solid #356859;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        #table-loader div {
            font-size: 14px;
            margin-top: 6px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .residency-table-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .styled-search-bar {
                max-width: 100%;
                width: 100%;
            }

            #ajax-table-container th, 
            #ajax-table-container td {
                font-size: 13px;
            }
        }
    </style>
</body>
</html>
