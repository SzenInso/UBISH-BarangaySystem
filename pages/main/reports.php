<?php
include '../../config/dbfetch.php';

// FETCHING DATA FOR CHARTS
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
    WHEN document_path LIKE '%.pdf' THEN 'PDF'
    WHEN document_path LIKE '%.docx' THEN 'DOCX'
    WHEN document_path LIKE '%.xlsx' THEN 'XLSX'
    WHEN document_path LIKE '%.jpg' THEN 'JPG'
    WHEN document_path LIKE '%.png' THEN 'PNG'
    ELSE 'Other'
    END AS document_type, 
        COUNT(*) AS total 
    FROM documents 
    GROUP BY document_type";
$stmt3 = $pdo->query($documentTypeFetch);
if (!$stmt3) {
    die('Error fetching document type data');
}
$documentTypeData = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML STARTS HERE -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UBISH Dashboard | Reports</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .chart-container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
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
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../main/documents.php">Documents</a></li>
                    <li><a href="../main/announcements.php">Post Announcement</a></li>
                    <h3>Tables & Requests</h3>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/employee_table.php">Employee Table</a></li>'; } ?>
                    <?php if ($accessLevel >= 3) { echo '<li><a href="../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="#">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="#">Permit Requests</a></li>'; } ?>
                    <h3>Reports</h3>
                    <li><a href="#">Incident Reports</a></li>
                    <li class="active"><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1>Generated Reports</h1>

                <!-- primary tabs -->
                <div class="tabs">
                    <button class="tab-btn active" id="announcementTabBtn" onclick="showPrimaryTab('announcements')">Announcements</button>
                    <button class="tab-btn" id="documentTabBtn" onclick="showPrimaryTab('documents')">Documents</button>
                </div>

                <!-- secondary tabs: announcements -->
                <div class="tabs" id="announcementTabs" style="display: block;">
                    <button class="tab-btn active" onclick="showSecondaryTab('categoryTab')">By Category</button>
                    <button class="tab-btn" onclick="showSecondaryTab('privacyTab')">By Privacy</button>
                </div>

                <!-- secondary tabs: documents -->
                <div class="tabs" id="documentTabs" style="display: none;">
                    <button class="tab-btn active" onclick="showSecondaryTab('documentTab')">By File Type</button>
                </div>

                <!-- charts: announcements -->
                <div class="chart-container" id="categoryTab" style="display: block;">
                    <h3>Announcements by Category</h3>
                    <canvas id="categoryChart"></canvas>
                </div>

                <div class="chart-container" id="privacyTab" style="display: none;">
                    <h3>Announcements by Privacy</h3>
                    <canvas id="privacyChart"></canvas>
                </div>

                <!-- charts: documents -->
                <div class="chart-container" id="documentTab" style="display: none;">
                    <h3>Documents by File Type</h3>
                    <canvas id="documentChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <hr>
        <p>&copy; <?php echo date('Y'); ?> | Unified Barangay Information Service Hub</p>
    </footer>

    <!-- JAVASCRIPT BLOCK STARTS HERE -->
    <script>
        // primary tab switching
        function showPrimaryTab(tab) {
            const announcementTabs = document.getElementById('announcementTabs');
            const documentTabs = document.getElementById('documentTabs');
            const announcementTabBtn = document.getElementById('announcementTabBtn');
            const documentTabBtn = document.getElementById('documentTabBtn');

            if (tab === 'announcements') {
                announcementTabs.style.display = 'block';
                documentTabs.style.display = 'none';
                announcementTabBtn.classList.add('active');
                documentTabBtn.classList.remove('active');
            } else {
                announcementTabs.style.display = 'none';
                documentTabs.style.display = 'block';
                announcementTabBtn.classList.remove('active');
                documentTabBtn.classList.add('active');
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
            } else {
                document.querySelectorAll('#documentTabs .tab-btn')[0].classList.add('active');
            }
        }


        // CHART JS: announcement categories
        const categoryLabels = <?php echo json_encode(array_column($categoryData, 'category')); ?>;
        const categoryCounts = <?php echo json_encode(array_column($categoryData, 'total')); ?>;

        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } }
            }
        });

        // CHART JS: announcement privacy
        const privacyLabels = <?php echo json_encode(array_column($privacyData, 'privacy')); ?>;
        const privacyCounts = <?php echo json_encode(array_column($privacyData, 'total')); ?>;

        const privacyCtx = document.getElementById('privacyChart').getContext('2d');
        const privacyChart = new Chart(privacyCtx, {
            type: 'doughnut',
            data: {
                labels: privacyLabels,
                datasets: [{
                    data: privacyCounts,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                aspectRatio: 1.5
            }
        });

        // CHART JS: document type
        const documentLabels = <?php echo json_encode(array_column($documentTypeData, 'document_type')); ?>;
        const documentCounts = <?php echo json_encode(array_column($documentTypeData, 'total')); ?>;

        const documentCtx = document.getElementById('documentChart').getContext('2d');
        const documentChart = new Chart(documentCtx, {
            type: 'pie',
            data: {
                labels: documentLabels,
                datasets: [{
                    data: documentCounts,
                    backgroundColor: [
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 159, 64, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                aspectRatio: 1.5
            }
        });

        showPrimaryTab('announcements');
    </script>
</body>
</html>
