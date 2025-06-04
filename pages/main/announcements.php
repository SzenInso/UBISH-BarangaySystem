<?php
include '../../config/dbfetch.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashPages.css">
    <link rel="stylesheet" href="css/announcements.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>UBISH Dashboard | Create Announcement</title>
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

        <!-- Main content -->
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
                    <h1 class="form-title">Post an Announcement</h1>
                    <div class="announcement-form-container">
                        <form method="POST" enctype="multipart/form-data" class="announcement-form">
                            <div class="form-group">
                                <label for="title">Announcement Title</label>
                                <input type="text" name="title" id="title" placeholder="Enter title" required>
                            </div>

                            <div class="form-group">
                                <label for="thumbnail">Add Thumbnail <span class="optional">(Optional)</span></label>
                                <input type="file" name="thumbnail" id="thumbnail" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label>Type of Privacy</label>
                                <div class="options-group">
                                    <label><input type="radio" name="privacy" value="Public" required> Public</label>
                                    <label><input type="radio" name="privacy" value="Private" required> Private</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Category</label>
                                <div class="options-group category-options">
                                    <label><input type="radio" name="category" value="Public Notice" required> Public Notice</label>
                                    <label><input type="radio" name="category" value="Report" required> Report</label>
                                    <label><input type="radio" name="category" value="Event" required> Event</label>
                                    <label><input type="radio" name="category" value="Emergency" required> Emergency</label>
                                    <label><input type="radio" id="categoryOthers" name="category" value="Others" required> Others</label>
                                </div>
                                <input type="text" id="customCategory" name="custom-category" placeholder="Enter custom category" style="display: none;">
                            </div>

                            <div class="form-group">
                                <label for="description">Announcement Description</label>
                                <textarea name="description" id="description" rows="5" placeholder="Enter description" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="attachments">Add Attachments <span class="optional">(Optional)</span></label>
                                <input type="file" name="attachments[]" id="attachments" multiple>
                            </div>

                            <div class="form-group">
                                <button name="post" id="postAnnouncement">Post Announcement</button>
                            </div>
                        </form>
                        <!-- ACTUAL CODE -->
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
                        <!-- DEBUG CODE -->
                        <?php include '../../config/debug/announcements_debug.php'; ?>
                    </div>
                </div>
        </main>

        <footer class="main-footer">
            <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
        </footer>
        <!-- ending of main content -->
         </div>
    <!-- ending of body wrapper -->
    </div>
    <!-- collapse sidebar function -->
    <script>
        // Show custom category input when "Others" is selected
        document.getElementById("categoryOthers").addEventListener("change", function () {
            document.getElementById("customCategory").style.display = "block";
        });

        // Hide custom input if other category is selected
        document.querySelectorAll("input[name='category']").forEach(function (el) {
            el.addEventListener("change", function () {
                if (el.value !== "Others") {
                    document.getElementById("customCategory").style.display = "none";
                }
            });
        });
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>