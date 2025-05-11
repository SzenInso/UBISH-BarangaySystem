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
    <title>UBISH Dashboard | Reports</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .chart-container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
        }

        .tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 5px;
        }

        .tab-btn {
            border: 2px solid gray;
            background-color: white;
            color: black;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .tab-btn:hover {
            background-color: lightgray;
        }

        .tab-btn.active {
            background-color: gray;
            color: white;
        }

        .tab-btn:focus {
            outline: none;
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
                        <li><button class="logout" name="logout">Log Out</button></li>
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
                    <li><a href="../main/account_creation.php">Account Creation</a></li>
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../main/documents.php">Documents</a></li>
                    <li><a href="../main/announcements.php">Post Announcement</a></li>
                    <h3>Tables & Requests</h3>
                    <li><a href="../main/employee_table.php">Employee Table</a></li>
                    <li><a href="../main/account_requests.php">Account Requests</a></li>
                    <li><a href="#">Certificate Requests</a></li>
                    <li><a href="#">Permit Requests</a></li>
                    <h3>Reports</h3>
                    <li><a href="../main/incidents.php">Incident Reports</a></li>
                    <li class="active"><a href="#">Analytics</a></li>
                </ul>
            </div>
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
        </div>
    </main>

    <footer>
        <hr>
        <p>&copy; <?php echo date('Y'); ?> | Unified Barangay Information Service Hub</p>
    </footer>

    <!-- JAVASCRIPT BLOCK STARTS HERE -->
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
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
            } else if (tab === 'documents') {
                announcementTabs.style.display = 'none';
                documentTabs.style.display = 'block';
                incidentTabs.style.display = 'none';
                announcementTabBtn.classList.remove('active');
                documentTabBtn.classList.add('active');
                incidentTabBtn.classList.remove('active');
            } else {
                announcementTabs.style.display = 'none';
                documentTabs.style.display = 'none';
                incidentTabs.style.display = 'block';
                announcementTabBtn.classList.remove('active');
                documentTabBtn.classList.remove('active');
                incidentTabBtn.classList.add('active');
            }

            showSecondaryTab('categoryTab');
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
</body>

</html>