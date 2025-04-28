<?php
session_start();
include '../../config/dbfetch.php';

// fetch documents
$documentsFetch = "SELECT * FROM documents";
$stmt = $pdo->query($documentsFetch);
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// upload handler
if (isset($_POST['upload'])) {
    $title = $_POST['title'];
    $uploadedBy = $_SESSION['user_id'];
    $uploadDate = date('Y-m-d H:i:s');

    $uploadDir = '../../uploads/documents/';
    $maxSize = 10 * 1024 * 1024;
    $errors = [];

    // file upload
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $fileTmpName = $_FILES['document']['tmp_name'];
        $fileName = uniqid() . '-' . basename($_FILES['document']['name']); // unique name for duplicates
        $fileSize = $_FILES['document']['size'];
        $fileType = $_FILES['document']['type'];

        // file size validation
        if ($fileSize > $maxSize) {
            $errors[] = "File exceeds 10MB size limit.";
        }

        // move file
        if (empty($errors)) {
            $filePath = $uploadDir . $fileName;
            if (move_uploaded_file($fileTmpName, $filePath)) {
                $insert = "INSERT INTO documents (document_name, document_path, uploaded_by, upload_date, title) 
                    VALUES (:document_name, :document_path, :uploaded_by, :upload_date, :title)";
                $stmt = $pdo->prepare($insert);
                $stmt->execute([
                    ':document_name' => htmlspecialchars($title),
                    ':document_path' => htmlspecialchars($filePath),
                    ':uploaded_by' => $uploadedBy,
                    ':upload_date' => $uploadDate,
                    ':title' => htmlspecialchars($title),
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

    // error display
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}

// generate documents table
function getDocumentsTable($pdo)
{
    $documentsFetch = "SELECT * FROM documents";
    $stmt = $pdo->query($documentsFetch);
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = "";
    foreach ($documents as $document) {
        $documentId = $document['document_id'];
        $title = htmlspecialchars($document['document_name']);
        $filePath = htmlspecialchars($document['document_path']);
        $uploadedBy = $document['uploaded_by'];
        $uploadDate = $document['upload_date'];

        $userFetch = "SELECT username FROM login_details WHERE user_id = :uploaded_by";
        $stmtUser = $pdo->prepare($userFetch);
        $stmtUser->execute([':uploaded_by' => $uploadedBy]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
        $username = $user ? $user['username'] : 'Unknown';

        $output .= "<tr>
                        <td>$title</td>
                        <td><a href='$filePath' download>Download</a></td>
                        <td>$username</td>
                        <td>$uploadDate</td>
                        <td><a href='#' onclick='confirmDelete($documentId)'>Delete</a></td>
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
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>UBISH Dashboard | Documents</title>
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
                    <li class="active"><a href="../main/dashboard.php">Home</a></li>
                    <li><a href="../main/account.php">Account</a></li>
                    <?php
                        // placeholder access control pages
                        if ($accessLevel >= 1) {
                            echo '<li><a href="../main/documents.php">Documents</a></li>';
                            echo '<li><a href="../main/announcements.php">Post Announcement</a></li>';
                        }
                        if ($accessLevel >= 2) {
                            echo '<li><a href="../main/employee_table.php">Employee Table</a></li>';
                        }
                        if ($accessLevel >= 3) {
                            echo '<li><a href="../main/account_requests.php">Account Requests</a></li>';
                        }
                    ?>
                    <li><a href="../main/reports.php">Reports</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1>
                    <center>Documents</center>
                </h1><br>
                <div class="document-upload-form">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="document-credentials">
                            <h3>Document Title</h3>
                            <input type="text" name="title" placeholder="Enter document title" required>
                        </div>
                        <div class="document-credentials">
                            <center><h3>Upload Document</h3></center>
                            <input type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.png"
                                required>
                            <button name="upload" id="uploadDocument">Upload</button>
                        </div>
                    </form>
                </div>

                <h2>
                    <center>Uploaded Documents</center>
                </h2>
                <table class="documents-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>File</th>
                            <th>Uploaded By</th>
                            <th>Upload Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        echo getDocumentsTable($pdo);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>

    <script>
        // delete confirmation
        function confirmDelete(documentId) {
            const confirmation = confirm("Are you sure you want to delete this document?");
            if (confirmation) {
                window.location.href = "delete_document.php?id=" + documentId;
            }
        }
    </script>
</body>

</html>
