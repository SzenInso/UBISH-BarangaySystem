<?php
include '../../config/dbfetch.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>UBISH Dashboard | Create Announcement</title>
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
                    <li class="active"><a href="../main/announcements.php">Post Announcement</a></li>
                    
                    <h3>Tables & Requests</h3>
                    <li><a href="../main/employee_table.php">Employee Table</a></li>

                    <!-- STANDARD -->
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
                <h1>
                    <center>Post an Announcement</center>
                </h1><br>
                <style>
                    .announcement-posting-form {
                        text-align: left;
                        margin: 0;
                        padding: 16px;
                        width: 100%;
                    }

                    .announcement-posting-form form {
                        display: block;
                        width: 100%;
                    }

                    .announcement-credentials {
                        margin-bottom: 16px;
                    }

                    .announcement-credentials input,
                    .announcement-credentials textarea {
                        width: 100%;
                        padding: 8px;
                        box-sizing: border-box;
                    }

                    .privacy-options,
                    .category-options {
                        display: flex;
                        gap: 32px;
                        align-items: center;
                        white-space: nowrap;
                    }

                    .privacy-options label,
                    .category-options label {
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        font-size: 14px;
                        cursor: pointer;
                    }
                </style>
                <div class="announcement-posting-form">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="announcement-credentials">
                            <h3>Announcement Title</h3>
                            <input type="text" name="title" placeholder="Enter title" required>
                        </div>
                        <div class="announcement-credentials">
                            <div style="display: flex;">
                                <h3>Add Thumbnail</h3>
                                <p>&nbsp;(Optional)</p>
                            </div>
                            <input type="file" name="thumbnail" accept="image/*">
                        </div>
                        <div class="announcement-credentials">
                            <h3>Type of Privacy</h3>
                            <div class="privacy-options">
                                <label>
                                    <input type="radio" id="privacy" name="privacy" value="Public" required>
                                    Public
                                </label>
                                <label>
                                    <input type="radio" id="privacy" name="privacy" value="Private" required>
                                    Private
                                </label>
                            </div>
                        </div>
                        <div class="announcement-credentials">
                            <h3>Category</h3>
                            <div class="category-options">
                                <label>
                                    <input type="radio" id="category" name="category" value="Public Notice" required>
                                    Public Notice
                                </label>
                                <label>
                                    <input type="radio" id="category" name="category" value="Report" required>
                                    Report
                                </label>
                                <label>
                                    <input type="radio" id="category" name="category" value="Event" required>
                                    Event
                                </label>
                                <label>
                                    <input type="radio" id="category" name="category" value="Emergency" required>
                                    Emergency
                                </label>
                                <label>
                                    <input type="radio" id="categoryOthers" name="category" value="Others" required>
                                    Others
                                </label>
                                <input type="text" id="customCategory" name="custom-category"
                                    placeholder="Enter custom category" style="display: none;">
                                <script src="../../assets/js/customCategory.js"></script>
                            </div>
                        </div>
                        <div class="announcement-credentials">
                            <h3>Announcement Description</h3>
                            <textarea name="description" rows="5" placeholder="Enter description" required></textarea>
                        </div>
                        <div class="announcement-credentials">
                            <div style="display: flex;">
                                <h3>Add Attachments</h3>
                                <p>&nbsp;(Optional)</p>
                            </div>
                            <input type="file" name="attachments[]" multiple>
                        </div>
                        <button name="post" id="postAnnouncement">Post Announcement</button>
                    </form>
                    <?php
                    if (isset($_POST['post'])) {
                        $title = $_POST['title'];
                        $privacy = $_POST['privacy'];
                        $category = $_POST['category'];
                        if ($category === "Others") {
                            $category = $_POST['custom-category'];
                        }
                        $description = $_POST['description'];
                        $author = $_SESSION['user_id']; // session user id is login_id from login_details table
                        $post_date = date('Y-m-d H:i:s');

                        // for upload directory
                        $uploadDir = '../../uploads/attachments/';
                        $maxSize = 10 * 1024 * 1024; // 10MB
                        $fileTimestamp = time();
                        $errors = [];

                        // thumbnail upload validation
                        $thumbnail = null;
                        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
                            $thumbnailTmpName = $_FILES['thumbnail']['tmp_name'];
                            $thumbnailError = $_FILES['thumbnail']['error'];
                            $thumbnailSize = $_FILES['thumbnail']['size'];
                            $allowedMIMETypes = array(
                                'image/jpg',
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                                'image/webp',
                                'image/bmp',
                                'image/tiff',
                                'image/vnd.microsoft.icon',
                                'image/svg+xml',
                                'image/heif',
                                'image/heic',
                                'image/jp2',
                                'image/avif',
                                'image/x-icon',
                                'image/apng'
                            );

                            if ($thumbnailError !== UPLOAD_ERR_OK) {
                                $errors[] = "Error uploading thumbnail.";
                            } elseif ($thumbnailSize > $maxSize) {
                                $errors[] = "Thumbnail " . basename($_FILES['thumbnail']['name']) . " exceeds 10MB size limit.";
                            } else {
                                $thumbnailMIMEType = mime_content_type($thumbnailTmpName);
                                if (!in_array($thumbnailMIMEType, $allowedMIMETypes)) {
                                    $errors[] = "Invalid file type for thumbnail. Only image files are allowed.";
                                } else {
                                    $thumbnailName = "thumbnail_" . $fileTimestamp . '_' . basename($_FILES['thumbnail']['name']);
                                    $thumbnailPath = $uploadDir . $thumbnailName;
                                }
                            }
                        }

                        // attachment upload validation
                        $validAttachments = [];
                        $allowedMimeTypes = [ // added allowed attachment MIME types (NOTE: add more?)
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'application/zip',
                            'audio/mpeg',
                            'video/mp4',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ];
                        if (!empty($_FILES['attachments']['name'][0])) {
                            foreach ($_FILES['attachments']['name'] as $index => $name) {
                                $tmpName = $_FILES['attachments']['tmp_name'][$index];
                                $size = $_FILES['attachments']['size'][$index];
                                $error = $_FILES['attachments']['error'][$index];

                                if ($error !== UPLOAD_ERR_OK) {
                                    $errors[] = "Error uploading attachment: $name.";
                                } elseif ($size > $maxSize) {
                                    $errors[] = "Attachment " . htmlspecialchars($name) . " exceeds 10MB size limit.";
                                } else {
                                    // added check for MIME types
                                    $fileMimeType = mime_content_type($tmpName);
                                    if (!in_array($fileMimeType, $allowedMimeTypes)) {
                                        $errors[] = "Invalid file type for attachment: $name. Only PDF, Word, Image, Zip, MP3, MP4, and Excel files are allowed.";
                                    } else {
                                        $uniqueName = "attachment_" . $fileTimestamp . '_' . basename($name); // unique name for dealing with duplicate files
                                        $filePath = $uploadDir . $uniqueName;

                                        // appends valid attachment to the array
                                        $validAttachments[] = [
                                            "originalName" => $name,
                                            "tmpName" => $tmpName,
                                            "filePath" => $filePath,
                                            "uniqueName" => $uniqueName
                                        ];
                                    }
                                }
                            }
                        }

                        // checks for errors
                        if (empty($errors)) {
                            // uploads thumbnail if present
                            if (isset($thumbnailPath)) {
                                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailPath)) {
                                    $thumbnail = $thumbnailPath;
                                } else {
                                    $errors[] = "Failed to move uploaded thumbnail.";
                                }
                            }

                            // uploads attachments if present
                            foreach ($validAttachments as $validAttachment) {
                                if (!move_uploaded_file($validAttachment['tmpName'], $validAttachment['filePath'])) {
                                    $errors[] = "Failed to move attachment: " . htmlspecialchars($validAttachment['originalName']);
                                }
                            }
                        }

                        if (empty($errors)) {
                            // insert announcement if no errors
                            $announcementQuery = "  INSERT INTO announcements (title, body, privacy, category, author_id, post_date, thumbnail) 
                                                        VALUES (:title, :body, :privacy, :category, :author_id, :post_date, :thumbnail)";
                            $stmt = $pdo->prepare($announcementQuery);
                            $stmt->execute([
                                ":title" => $title,
                                ":body" => $description,
                                ":privacy" => $privacy,
                                ":category" => $category,
                                ":author_id" => $author,
                                ":post_date" => $post_date,
                                ":thumbnail" => $thumbnail
                            ]);

                            // inserts attachments if no errors
                            $announcement_id = $pdo->lastInsertId();
                            foreach ($validAttachments as $validAttachment) {
                                $attachmentQuery = "INSERT INTO attachments (announcement_id, file_path, file_name, upload_date) 
                                                        VALUES (:announcement_id, :file_path, :file_name, :upload_date)";
                                $stmtAttachment = $pdo->prepare($attachmentQuery);
                                $stmtAttachment->execute([
                                    ":announcement_id" => $announcement_id,
                                    ":file_path" => $validAttachment['filePath'],
                                    ":file_name" => $validAttachment['uniqueName'],
                                    ":upload_date" => $post_date
                                ]);
                            }

                            echo "
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        Swal.fire({
                                            title: 'Post successful.',
                                            text: 'Announcement has been posted successfully.',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            window.location.href='../main/dashboard.php';
                                        });
                                    });
                                </script>
                            ";
                        } else {
                            foreach ($errors as $err) {
                                echo "<p style='color:red;'>$err</p><br>";
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>