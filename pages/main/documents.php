<?php
session_start();
include '../../config/dbfetch.php';

// file fetch
$documentsFetch = "SELECT * FROM files";
$stmt = $pdo->query($documentsFetch);
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['upload'])) {
    $title = $_POST['title'];
    $uploadedBy = $_SESSION['user_id'];
    $uploadDate = date('Y-m-d H:i:s');
    $privacy = 'Public'; // default

    $uploadDir = '../../uploads/documents/';
    $maxSize = 10 * 1024 * 1024;
    $errors = [];

    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $fileTmpName = $_FILES['document']['tmp_name'];
        $fileName = uniqid() . '-' . basename($_FILES['document']['name']);
        $fileSize = $_FILES['document']['size'];
        $fileType = $_FILES['document']['type']; // MIME type

        if ($fileSize > $maxSize) {
            $errors[] = "File exceeds 10MB size limit.";
        }

        if (empty($errors)) {
            $filePath = $uploadDir . $fileName;
            if (move_uploaded_file($fileTmpName, $filePath)) {
                $insert = "INSERT INTO files (file_name, file_path, uploaded_by, upload_date, title, privacy, file_type) 
                    VALUES (:file_name, :file_path, :uploaded_by, :upload_date, :title, :privacy, :file_type)";
                $stmt = $pdo->prepare($insert);
                $stmt->execute([
                    ':file_name' => htmlspecialchars($title),
                    ':file_path' => htmlspecialchars($filePath),
                    ':uploaded_by' => $uploadedBy,
                    ':upload_date' => $uploadDate,
                    ':title' => htmlspecialchars($title),
                    ':privacy' => $privacy,
                    ':file_type' => $fileType
                ]);

                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $errors[] = "Failed to upload the file.";
            }
        }
    } else {
        $errors[] = "No file selected or an error occurred during file upload.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}

// generate documents table
function getDocumentsTable($pdo, $currentUserId)
{
    $documentsFetch = "SELECT * FROM files";
    $stmt = $pdo->query($documentsFetch);
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = "";

    $mimeMap = [
        'application/pdf' => 'PDF',
        'application/msword' => 'Word',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Word',
        'application/vnd.ms-excel' => 'Excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Excel',
        'image/jpeg' => 'JPEG',
        'image/png' => 'PNG',
        'text/plain' => 'Text'
    ];

    foreach ($documents as $document) {
        $documentId = $document['file_id'];
        $title = htmlspecialchars($document['file_name']);
        $filePath = htmlspecialchars($document['file_path']);

        $rawType = $document['file_type'] ?? 'unknown/unknown';
        $fileType = $mimeMap[$rawType] ?? strtoupper(explode('/', $rawType)[1] ?? 'Unknown');

        $uploadedBy = $document['uploaded_by'];
        $uploadDate = $document['upload_date'];

        // username fetch
        $userFetch = "SELECT username FROM login_details WHERE user_id = :uploaded_by";
        $stmtUser = $pdo->prepare($userFetch);
        $stmtUser->execute([':uploaded_by' => $uploadedBy]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
        $username = $user ? $user['username'] : 'Unknown';

        $actionBtn = $uploadedBy == $currentUserId
            ? "<button onclick='confirmDelete($documentId)'>Delete</button>"
            : "<button onclick='confirmDelete($documentId)'>Delete</button>"; // delete override for admin

        $output .= "<tr>
                        <td>$title</td>
                        <td><a href='$filePath' download>Download</a></td>
                        <td>$fileType</td>
                        <td>$username</td>
                        <td>$uploadDate</td>
                        <td>$actionBtn</td>
                    </tr>";
    }

    return $output;
}
?>

<!-- HTML STARTS HERE -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/dashPages.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Greenwater Village Dashboard | Documents</title>

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

        <main class="content">
                <div class="dashboard-content">
                    <h1><center>Documents</center></h1><br>
                    <div class="document-upload-form">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="document-credentials">
                                <h3>Document Title</h3>
                                <input type="text" name="title" placeholder="Enter document title" required>
                            </div>
                            <div class="document-credentials">
                                <h3>Upload Document</h3>
                                <input type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.png" required>
                                <button name="upload" id="uploadDocument">Upload</button>
                            </div>
                        </form>
                    </div>

                    <h2><center>Uploaded Documents</center></h2>
                    <table class="documents-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>File</th>
                                <th>Type</th>
                                <th>Uploaded By</th>
                                <th>Upload Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo getDocumentsTable($pdo, $_SESSION['user_id']); ?>
                        </tbody>
                    </table>
                </div>
        </main>
            <footer class="main-footer">
                <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
            </footer>

        <script>
            function confirmDelete(documentId) {
                Swal.fire({
                    title: 'Delete Document?',
                    text: "Are you sure you want to delete this document? This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#4caf50',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "delete_document.php?id=" + documentId;
                    }
                });
            }

            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');

            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
            });
        </script>
        <script src="../../assets/js/sweetalert2.js"></script>
        <!-- ending for main content -->
         </div>
    <!-- ending for class wrapper -->
     </div>

     <style>
        /* Headings */
        .dashboard-content h1,
        .dashboard-content h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 15px;
        }

        /* Upload form container */
        .document-upload-form {
            background-color: #f1f8e9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 64, 0, 0.05);
        }

        /* Input sections */
        .document-credentials {
            margin-bottom: 20px;
        }
        .document-credentials h3 {
            margin-bottom: 8px;
            color: #33691e;
        }

        /* Input styling */
        .document-credentials input[type="text"],
        .document-credentials input[type="file"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #a5d6a7;
            background-color: #ffffff;
            font-size: 0.95rem;
        }
        .document-credentials input[type="text"]:focus,
        .document-credentials input[type="file"]:focus {
            outline: none;
            border-color: #66bb6a;
        }

        /* Upload button */
        #uploadDocument {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #43a047;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.95rem;
            transition: background-color 0.3s ease;
        }
        #uploadDocument:hover {
            background-color: #2e7d32;
        }

        /* Documents table */
        .documents-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(0, 64, 0, 0.05);
        }
        .documents-table thead {
            background-color: #a5d6a7;
        }
        .documents-table thead th {
            padding: 12px;
            color: #1b5e20;
            text-align: left;
        }
        .documents-table tbody td {
            padding: 12px;
            border-bottom: 1px solid #c8e6c9;
            color: #2e7d32;
        }
        .documents-table tbody tr:nth-child(even) {
            background-color: #f9fbe7;
        }

        /* Action buttons (View/Download/Delete) */
        .documents-table .action-btn {
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .view-btn {
            background-color: #66bb6a;
            color: white;
        }
        .download-btn {
            background-color: #43a047;
            color: white;
        }
        .delete-btn {
            background-color: #e53935;
            color: white;
        }
        .view-btn:hover {
            background-color: #388e3c;
        }
        .download-btn:hover {
            background-color: #2e7d32;
        }
        .delete-btn:hover {
            background-color: #c62828;
        }

        /* Responsive Table */
        @media (max-width: 768px) {
            .documents-table,
            .documents-table thead,
            .documents-table tbody,
            .documents-table th,
            .documents-table td,
            .documents-table tr {
                display: block;
            }

            .documents-table thead {
                display: none;
            }

            .documents-table tbody tr {
                margin-bottom: 15px;
                background-color: #f1f8e9;
                padding: 15px;
                border-radius: 10px;
            }

            .documents-table td {
                padding: 8px;
                text-align: left;
                position: relative;
            }

            .documents-table td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 6px;
                color: #33691e;
            }
        }
     </style>

</body>
</html>
