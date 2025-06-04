<?php
include '../../config/dbfetch.php';

// Fetching data for other charts
// announcements: category data
$categoryFetch = "SELECT category, COUNT(*) AS total FROM announcements GROUP BY category";
$stmt1 = $pdo->query($categoryFetch);
if (!$stmt1) {
    die('Error fetching category data');
}
$categoryData = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// announcements: privacy data
$privacyFetch = "SELECT privacy, COUNT(*) AS total FROM announcements GROUP BY privacy";
$stmt2 = $pdo->query($privacyFetch);
if (!$stmt2) {
    die('Error fetching privacy data');
}
$privacyData = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// documents: file type data
$documentTypeFetch = "SELECT CASE
    WHEN file_path LIKE '%.pdf' THEN 'PDF'
    WHEN file_path LIKE '%.docx' THEN 'DOCX'
    WHEN file_path LIKE '%.xlsx' THEN 'XLSX'
    WHEN file_path LIKE '%.jpg' THEN 'JPG'
    WHEN file_path LIKE '%.png' THEN 'PNG'
    ELSE 'Other'
    END AS document_type, 
        COUNT(*) AS total 
    FROM files 
    GROUP BY document_type";
$stmt3 = $pdo->query($documentTypeFetch);
if (!$stmt3) {
    die('Error fetching document type data');
}
$documentTypeData = $stmt3->fetchAll(PDO::FETCH_ASSOC);

// incidents: type of incidents
$incidentTypeFetch = "SELECT incident_type, COUNT(*) AS total FROM incidents GROUP BY incident_type";
$stmt4 = $pdo->query($incidentTypeFetch);
if (!$stmt4) {
    die('Error fetching incident type data');
}
$incidentTypeData = $stmt4->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashPages.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <title>Greenwater Village Dashboard | Incident History</title>
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
                    <li><a href="../main/permits.php"><i class="fas fa-id-badge"></i> Permit Requests</a></li>
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

            <main class="content">
                <div class="dashboard-content">
                    <h1>Generated Reports</h1>
                    <br>
                    <!-- primary tabs -->
                    <div class="tabs">
                        <button class="tab-btn active" id="announcementTabBtn"
                            onclick="showPrimaryTab('announcements')">Announcements</button>
                        <button class="tab-btn" id="documentTabBtn" onclick="showPrimaryTab('documents')">Documents</button>
                        <button class="tab-btn" id="incidentTabBtn" onclick="showPrimaryTab('incidents')">Incidents</button>
                    </div>
                    <br>
                    <!-- secondary tabs: announcements -->
                    <div class="tabs" id="announcementTabs" style="display: block;">
                        <button class="tab-btn active" onclick="showSecondaryTab('categoryTab')">By Category</button>
                        <button class="tab-btn" onclick="showSecondaryTab('privacyTab')">By Privacy</button>
                    </div>

                    <!-- secondary tabs: documents -->
                    <div class="tabs" id="documentTabs" style="display: none;">
                        <button class="tab-btn active" onclick="showSecondaryTab('documentTab')">By File Type</button>
                    </div>

                    <!-- secondary tabs: incidents -->
                    <div class="tabs" id="incidentTabs" style="display: none;">
                        <button class="tab-btn active" onclick="showSecondaryTab('incidentTypeTab')">By Incident
                            Type</button>
                    </div>

                    <!-- charts: announcements -->
                    <div class="chart-container" id="categoryTab" style="display: block;">
                        <h3>Announcements by Category</h3>
                        <div id="categoryChart"></div>
                    </div>

                    <div class="chart-container" id="privacyTab" style="display: none;">
                        <h3>Announcements by Privacy</h3>
                        <div id="privacyChart"></div>
                    </div>

                    <!-- charts: documents -->
                    <div class="chart-container" id="documentTab" style="display: none;">
                        <h3>Documents by File Type</h3>
                        <div id="documentChart"></div>
                    </div>

                    <!-- charts: incidents -->
                    <div class="chart-container" id="incidentTypeTab" style="display: none;">
                        <h3>Incidents by Type</h3>
                        <div id="incidentTypeChart"></div>
                    </div>
                </div>
            </main>

            <footer class="main-footer">
                <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
            </footer>
            <!-- ending for the main content -->
        </div> 
    <!-- ending for class wrapper -->
    </div> 


    <!-- JAVASCRIPT BLOCK STARTS HERE -->
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        // primary tab switching
        // primary tab switching
        function showPrimaryTab(tab) {
            const announcementTabs = document.getElementById('announcementTabs');
            const documentTabs = document.getElementById('documentTabs');
            const incidentTabs = document.getElementById('incidentTabs');
            const announcementTabBtn = document.getElementById('announcementTabBtn');
            const documentTabBtn = document.getElementById('documentTabBtn');
            const incidentTabBtn = document.getElementById('incidentTabBtn');

            if (tab === 'announcements') {
                announcementTabs.style.display = 'block';
                documentTabs.style.display = 'none';
                incidentTabs.style.display = 'none';
                announcementTabBtn.classList.add('active');
                documentTabBtn.classList.remove('active');
                incidentTabBtn.classList.remove('active');
                showSecondaryTab('categoryTab'); // Automatically switch to the first secondary tab (By Category)
            } else if (tab === 'documents') {
                announcementTabs.style.display = 'none';
                documentTabs.style.display = 'block';
                incidentTabs.style.display = 'none';
                announcementTabBtn.classList.remove('active');
                documentTabBtn.classList.add('active');
                incidentTabBtn.classList.remove('active');
                showSecondaryTab('documentTab'); // Automatically switch to the first secondary tab (By File Type)
            } else {
                announcementTabs.style.display = 'none';
                documentTabs.style.display = 'none';
                incidentTabs.style.display = 'block';
                announcementTabBtn.classList.remove('active');
                documentTabBtn.classList.remove('active');
                incidentTabBtn.classList.add('active');
                showSecondaryTab('incidentTypeTab'); // Automatically switch to the first secondary tab (By Incident Type)
            }
        }

        // secondary tab switching
        function showSecondaryTab(tabId) {
            const tabs = document.querySelectorAll('.chart-container');
            tabs.forEach(tab => tab.style.display = 'none');

            const activeTab = document.getElementById(tabId);
            activeTab.style.display = 'block';

            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => btn.classList.remove('active'));

            if (tabId === 'categoryTab') {
                document.querySelectorAll('#announcementTabs .tab-btn')[0].classList.add('active');
            } else if (tabId === 'privacyTab') {
                document.querySelectorAll('#announcementTabs .tab-btn')[1].classList.add('active');
            } else if (tabId === 'documentTab') {
                document.querySelectorAll('#documentTabs .tab-btn')[0].classList.add('active');
            } else if (tabId === 'incidentTypeTab') {
                document.querySelectorAll('#incidentTabs .tab-btn')[0].classList.add('active');
            }
        }

        // HIGHCHARTS: announcement categories
        Highcharts.chart('categoryChart', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Announcements by Category'
            },
            xAxis: {
                categories: <?php echo json_encode(array_column($categoryData, 'category')); ?>
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total'
                }
            },
            series: [{
                name: 'Category',
                data: <?php echo json_encode(array_column($categoryData, 'total')); ?>,
                color: 'rgba(54, 162, 235, 0.6)'
            }],
            exporting: {
                enabled: true, // Enable exporting
                buttons: {
                    contextButton: {
                        menuItems: [
                            'downloadPNG',
                            'downloadJPEG',
                            'downloadPDF',
                            'downloadSVG',
                            'downloadCSV',
                            'downloadXLS'
                        ]
                    }
                }
            }
        });


        // HIGHCHARTS: announcement privacy
        Highcharts.chart('privacyChart', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Announcements by Privacy'
            },
            series: [{
                name: 'Privacy',
                data: <?php echo json_encode(array_map(function ($item) {
                    return ['name' => $item['privacy'], 'y' => $item['total']];
                }, $privacyData)); ?>,
                colors: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)']
            }],
            exporting: {
                enabled: true, // Enable exporting
                buttons: {
                    contextButton: {
                        menuItems: [
                            'downloadPNG',
                            'downloadJPEG',
                            'downloadPDF',
                            'downloadSVG',
                            'downloadCSV',
                            'downloadXLS'
                        ]
                    }
                }
            }
        });


        // HIGHCHARTS: document type
        Highcharts.chart('documentChart', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Documents by File Type'
            },
            series: [{
                name: 'File Type',
                data: <?php echo json_encode(array_map(function ($item) {
                    return ['name' => $item['document_type'], 'y' => $item['total']];
                }, $documentTypeData)); ?>,
                colors: ['rgba(255, 159, 64, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(153, 102, 255, 0.6)', 'rgba(255, 99, 132, 0.6)']
            }],
            exporting: {
                enabled: true,
                buttons: {
                    contextButton: {
                        menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
                    }
                }
            }
        });


        // HIGHCHARTS: incident types
        Highcharts.chart('incidentTypeChart', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Incidents by Type'
            },
            series: [{
                name: 'Incident Type',
                data: <?php echo json_encode(array_map(function ($item) {
                    return ['name' => $item['incident_type'], 'y' => $item['total']];
                }, $incidentTypeData)); ?>,
                colors: [
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(54, 162, 235, 0.6)'
                ]
            }],
            exporting: {
                enabled: true,
                buttons: {
                    contextButton: {
                        menuItems: [
                            'downloadPNG',
                            'downloadJPEG',
                            'downloadPDF',
                            'downloadSVG',
                            'downloadCSV',
                            'downloadXLS'
                        ]
                    }
                }
            }
        });

        showPrimaryTab('announcements');
    </script>

        <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
    <style>
        /* Title styling */
        .dashboard-content h1 {
            font-size: 28px;
            color: #2e5e4d;
            margin-bottom: 10px;
            text-align: center;
        }

        /* Tab buttons */
        .tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tab-btn {
            background-color: #d7ede2;
            color: #2e5e4d;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .tab-btn:hover {
            background-color: #a6dcb9;
        }

        .tab-btn.active {
            background-color: #2e5e4d;
            color: #fff;
        }

        /* Chart container */
        .chart-container {
            background-color: #ffffff;
            padding: 25px 30px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            animation: fadeIn 0.4s ease-in-out;
        }

        .chart-container h3 {
            font-size: 20px;
            color: #2e5e4d;
            margin-bottom: 15px;
        }

        /* Chart placeholders (for libraries like Chart.js, ApexCharts, etc.) */
        .chart-container > div {
            width: 100%;
            min-height: 300px;
        }

        /* Responsive behavior */
        @media (max-width: 768px) {
            .tab-btn {
                flex: 1 1 auto;
                font-size: 14px;
                padding: 10px;
            }

            .chart-container {
                padding: 20px;
            }

            .chart-container h3 {
                font-size: 18px;
            }
        }

        /* Fade-in animation for switching charts */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>