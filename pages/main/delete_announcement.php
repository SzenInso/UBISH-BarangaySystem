<?php
include '../../config/dbfetch.php';

// handles querying for the announcement to delete
if (isset($_GET['announcement_id'])) {
    $announcementID = $_GET['announcement_id'];

    // fetches single row of certain announcement for deletion
    $announcement = toBeDeletedAnnouncement($pdo, $announcementID);
    if (!$announcement) {
        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'No announcement found.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href='../main/dashboard.php';
                    });
                });
            </script>
        ";
        
    }

    // fetch attachments separately
    $attachmentQuery = "SELECT * FROM attachments WHERE announcement_id = :announcement_id";
    $attachmentsStmt = $pdo->prepare($attachmentQuery);
    $attachmentsStmt->execute([":announcement_id" => $announcementID]);
    $attachments = $attachmentsStmt->fetchAll();

} else {
    echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Invalid announcement ID.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href='../main/dashboard.php';
                });
            });
        </script>
    ";
}

if (isset($_POST['delete-announcement'])) {
    // requests delete from attachments and announcements table
    try {
        $pdo->beginTransaction();

        // physically deletes attachments
        foreach ($attachments as $attach) {
            $filePath = '../../uploads/attachments/' . $attach['file_path'];
            if (!empty($attach['file_path']) && file_exists($filePath)) {
                unlink($filePath);
            }
        }
        if (!empty($announcement['thumbnail']) && file_exists($announcement['thumbnail'])) {
            unlink('../../uploads/attachments/' . $announcement['thumbnail']);
        }

        $deleteAttachmentQuery = "DELETE FROM attachments WHERE announcement_id = :announcement_id";
        $stmt1 = $pdo->prepare($deleteAttachmentQuery);
        $stmt1->execute([":announcement_id" => $announcementID]);

        $deleteAnnouncementQuery = "DELETE FROM announcements WHERE announcement_id = :announcement_id";
        $stmt2 = $pdo->prepare($deleteAnnouncementQuery);
        $stmt2->execute([":announcement_id" => $announcementID]);

        if ($pdo->commit()) {
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Announcement deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href='../main/dashboard.php';
                        });
                    });
                </script>
            ";
        } else {
            $pdo->rollBack();
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Failed to delete announcement.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href='../main/dashboard.php';
                        });
                    });
                </script>
            ";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log('Error: ' . $e->getMessage());
        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Error occurred.',
                        text: 'An error occurred while deleting announcement.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href='../main/dashboard.php';
                    });
                });
            </script>
        ";
    }

}

if (isset($_POST['cancel'])) {
    header("location: ../main/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>UBISH Dashboard | Delete Announcement</title>
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
                    <h3>Home</h3>
                    <li><a href="../main/dashboard.php">Home</a></li>
                    <li><a href="../main/account.php">Account</a></li>
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../main/documents.php">Documents</a></li>
                    <li class="active"><a href="../main/announcements.php">Post Announcement</a></li>
                    <h3>Tables & Requests</h3>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/employee_table.php">Employee Table</a></li>'; } ?>
                    <?php if ($accessLevel >= 3) { echo '<li><a href="../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/certificates.php">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/permits.php">Permit Requests</a></li>'; } ?>
                    <h3>Reports</h3>
                    <li><a href="../main/incidents.php">Incident Reports</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1>Delete Announcement</h1>
                <p>Are you sure you want to delete this announcement?</p>
                <form method="POST">
                    <div class="delete-announcement-actions">
                        <button name="delete-announcement">Delete</button>
                        <button name="cancel">Cancel</button>
                    </div>
                </form>
                <div class="announcement-card" id="deleteAnnouncement">
                    <h2><?php echo htmlspecialchars($announcement['title']); ?></h2>
                    <p>
                        <strong>Issued By:</strong>&nbsp;
                        <?php echo htmlspecialchars($announcement['first_name'] . ' ' . $announcement['last_name']); ?>
                        <i>(<?php echo htmlspecialchars($announcement['username']); ?>)</i>
                    </p>
                    <p>
                        <?php
                        echo !empty($announcement['post_date'])
                            ? date("F j, Y g:i:s A", strtotime($announcement['post_date']))
                            : 'No Date Provided';
                        ?>
                    </p>
                    <p id="badge"><?php echo htmlspecialchars($announcement['category']); ?></p><br>
                    <?php if (!empty($announcement['thumbnail'])) { ?>
                        <img src="<?php echo htmlspecialchars($announcement['thumbnail']); ?>"
                            alt="thumbnail_<?php echo htmlspecialchars($announcement['announcement_id']); ?>"
                            id="announcementThumbnail">
                    <?php } ?>
                    <p id="announcementBody"><?php echo nl2br(htmlspecialchars($announcement['body'])); ?></p>
                    <?php if (!empty($attachments)) { ?>
                        <div class="announcement-attachment">
                            <h2>Attachments:</h2>
                            <?php foreach ($attachments as $attach) { ?>
                                <div>
                                    <a href="<?php echo htmlspecialchars($attach['file_path']); ?>" target="_blank">
                                        <?php echo htmlspecialchars($attach['file_name']); ?>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
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
