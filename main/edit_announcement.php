<?php
include '../../config/dbfetch.php';

/*
if (isset($_GET['announcement_id'])) {
    $announcementID = $_GET['announcement_id'];
    echo "Editing announcement with ID: " . htmlspecialchars($announcementID);
} else {
    echo "No announcement ID provided.";
}
*/

// access level verification
if (!isset($_SESSION['user_id']) || $accessLevel < 1) {
    header("Location: ../main/dashboard.php");
    exit;
}

if (isset($_GET['announcement_id'])) {
    $announcementID = $_GET['announcement_id'];

    // fetch announcements and attachments
    $announcementFetch = "SELECT * FROM announcements WHERE announcement_id = :announcement_id";
    $stmt = $pdo->prepare($announcementFetch);
    $stmt->execute([":announcement_id" => $announcementID]);
    $announcement = $stmt->fetch();

    if (!$announcement) {
        echo "<script>alert('Announcement not found.'); window.location.href = '../main/dashboard.php';</script>";
        exit;
    }

    $attachmentFetch = "SELECT * FROM attachments WHERE announcement_id = :announcement_id";
    $stmt = $pdo->prepare($attachmentFetch);
    $stmt->execute([":announcement_id" => $announcementID]);
    $attachments = $stmt->fetchAll();
} else {
    echo "<script>alert('Invalid announcement ID.'); window.location.href = '../main/dashboard.php';</script>";
    exit;
}

if (isset($_POST['update-announcement'])) {
    $title = $_POST['title'];
    $privacy = $_POST['privacy'];
    $category = $_POST['category'] === "Others" ? $_POST['custom-category'] : $_POST['category'];
    $description = $_POST['description'];
    $post_date = date('Y-m-d H:i:s');

    $uploadDir = '../../uploads/attachments/';
    $maxSize = 10 * 1024 * 1024;
    $fileTimestamp = time();
    $errors = [];

    // thumbnail upload
    $thumbnail = $announcement['thumbnail']; // existing thumbnail is default
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

        // delete old thumbnail
        if ($thumbnail && file_exists($thumbnail)) {
            unlink($thumbnail);
        }

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

                if (move_uploaded_file($thumbnailTmpName, $thumbnailPath)) {
                    $thumbnail = $thumbnailPath;
                } else {
                    $errors[] = "Failed to upload thumbnail.";
                }
            }
        }
    } else {
        $thumbnail = $announcement['thumbnail'];
    }

    // attachments upload
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
                    $uniqueName = "attachment_" . $fileTimestamp . '_' . basename($name); // unique name for duplicates
                    $filePath = $uploadDir . $uniqueName;

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

    // attachment deletion
    if (isset($_POST['delete_attachments'])) {
        foreach ($_POST['delete_attachments'] as $attachmentId) {
            $attachmentFetch = "SELECT file_path FROM attachments WHERE attachment_id = :attachment_id";
            $stmt = $pdo->prepare($attachmentFetch);
            $stmt->execute([":attachment_id" => $attachmentId]);
            $attachment = $stmt->fetch();
    
            if ($attachment) {
                // server delete
                $filePath = '../../uploads/attachments/' . $attachment['file_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                // database delete
                $deleteAttachment = "DELETE FROM attachments WHERE attachment_id = :attachment_id";
                $stmt = $pdo->prepare($deleteAttachment);
                $stmt->execute([":attachment_id" => $attachmentId]);
            } else {
                echo "<script>alert('Attachment not found in database.');</script>";
            }
        }
    }
    

    // error check
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Update announcement
            $updateAnnouncement = "UPDATE announcements 
                SET title = :title, body = :body, privacy = :privacy, category = :category, 
                post_date = :post_date, thumbnail = :thumbnail, last_updated = :last_updated 
                WHERE announcement_id = :announcement_id";


            $stmt = $pdo->prepare($updateAnnouncement);
            $stmt->execute([
                ":title" => $title,
                ":body" => $description,
                ":privacy" => $privacy,
                ":category" => $category,
                ":post_date" => $post_date,
                ":thumbnail" => $thumbnail,
                ":last_updated" => date('Y-m-d H:i:s'),
                ":announcement_id" => $announcementID

            ]);

            // file upload and update attachments
            if (!empty($validAttachments)) {
                foreach ($validAttachments as $file) {
                    if (move_uploaded_file($file["tmpName"], $file["filePath"])) {
                        $insertAttachment = "INSERT INTO attachments (announcement_id, file_name, file_path) 
                            VALUES (:announcement_id, :file_name, :file_path)";
                        $stmt = $pdo->prepare($insertAttachment);
                        $stmt->execute([
                            ":announcement_id" => $announcementID,
                            ":file_name" => $file['uniqueName'],
                            ":file_path" => $file['filePath'],
                        ]);
                    } else {
                        throw new Exception("Failed to move uploaded file: " . $file["originalName"]);
                    }
                }
            }

            $pdo->commit();
            echo "<script>alert('Announcement updated successfully.'); window.location.href = '../main/dashboard.php';</script>";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = '../main/dashboard.php';</script>";
        }
    } else {
        foreach ($errors as $err) {
            echo "<p style='color:red;'>$err</p><br>";
        }
    }
}

?>

<!-- HTML STARTS HERE -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>UBISH Dashboard | Edit Announcement</title>
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
                        <li><button class="logout" style="cursor: pointer;" name="logout">Log Out</button></li>
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
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/employee_table.php">Employee Table</a></li>'; } ?>
                    <?php if ($accessLevel >= 3) { echo '<li><a href="../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="#">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="#">Permit Requests</a></li>'; } ?>
                    <h3>Reports</h3>
                    <li><a href="../main/incidents.php">Incident Reports</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1>Edit Announcement</h1>
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
                            <input type="text" name="title"
                                value="<?php echo htmlspecialchars($announcement['title']); ?>" required>
                        </div>
                        <div class="announcement-credentials">
                            <div style="display: flex;">
                                <h3>Thumbnail</h3>
                            </div>
                            <input type="file" name="thumbnail" accept="image/*">
                            <?php if ($announcement['thumbnail']) { ?>
                                <p>Current Thumbnail: <img src="<?php echo htmlspecialchars($announcement['thumbnail']); ?>"
                                        alt="Current Thumbnail" width="100"></p>
                            <?php } ?>
                        </div>
                        <div class="announcement-credentials">
                            <h3>Privacy</h3>
                            <div class="privacy-options">
                                <label><input type="radio" name="privacy" value="Public" <?php echo ($announcement['privacy'] == 'Public') ? 'checked' : ''; ?> required>
                                    Public</label>
                                <label><input type="radio" name="privacy" value="Private" <?php echo ($announcement['privacy'] == 'Private') ? 'checked' : ''; ?> required>
                                    Private</label>
                            </div>
                        </div>
                        <div class="announcement-credentials">
                            <h3>Category</h3>
                            <div class="category-options">
                                <label><input type="radio" name="category" value="Public Notice" <?php echo ($announcement['category'] == 'Public Notice') ? 'checked' : ''; ?> required>
                                    Public
                                    Notice</label>
                                <label><input type="radio" name="category" value="Report" <?php echo ($announcement['category'] == 'Report') ? 'checked' : ''; ?>> Report</label>
                                <label><input type="radio" name="category" value="Event" <?php echo ($announcement['category'] == 'Event') ? 'checked' : ''; ?>> Event</label>
                                <label><input type="radio" name="category" value="Emergency" <?php echo ($announcement['category'] == 'Emergency') ? 'checked' : ''; ?>> Emergency</label>
                                <label><input type="radio" id="categoryOthers" name="category" value="Others" <?php echo (!in_array($announcement['category'], ['Public Notice', 'Report', 'Event', 'Emergency']) ? 'checked' : ''); ?>> Others</label>
                                <input type="text" id="customCategory" name="custom-category"
                                    placeholder="Enter custom category"
                                    value="<?php echo (!in_array($announcement['category'], ['Public Notice', 'Report', 'Event', 'Emergency']) ? htmlspecialchars($announcement['category']) : ''); ?>"
                                    style="<?php echo (!in_array($announcement['category'], ['Public Notice', 'Report', 'Event', 'Emergency']) ? 'display:block;' : 'display:none;'); ?>">
                                <script src="../../assets/js/customCategory.js"></script>
                            </div>
                        </div>
                        <div class="announcement-credentials">
                            <h3>Announcement Description</h3>
                            <textarea name="description" rows="5"
                                required><?php echo htmlspecialchars($announcement['body']); ?></textarea>
                        </div>
                        <div class="announcement-credentials">
                            <div style="display: flex;">
                                <h3>Add More Attachments</h3>
                            </div>
                            <input type="file" name="attachments[]" multiple>
                            <?php if (!empty($attachments)) { ?>
                                <div class="announcement-credentials">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th><h3>Current Attachments:</h3></th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($attachments as $attach) { ?>
                                                <tr>
                                                    <td>
                                                        <a href="../../uploads/attachments/<?php echo htmlspecialchars($attach['file_path']); ?>"
                                                            target="_blank" style="text-decoration: none; color: #007bff;">
                                                            <?php echo htmlspecialchars($attach['file_name']); ?>
                                                        </a>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <label>
                                                            <input type="checkbox" name="delete_attachments[]"
                                                                value="<?php echo htmlspecialchars($attach['attachment_id']); ?>">
                                                        </label>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                </ul>
                            <?php } ?>

                        </div>
                        <button name="update-announcement" id="postAnnouncement">Update Announcement</button>
                        <a href="../main/dashboard.php"><button type="button" id="postAnnouncement">Cancel</button></a>
                    </form>
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
