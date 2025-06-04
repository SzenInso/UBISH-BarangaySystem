<?php
include '../../config/dbfetch.php';

// access level verification
if (!isset($_SESSION['user_id']) || $accessLevel < 1) {
    header("Location: ../main/dashboard.php");
    exit;
}

// request verification
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../main/dashboard.php');
    exit;
}

// announcement id verification
if (!isset($_POST['announcement_id'])) {
    header('Location: ../main/dashboard.php');
    exit;
}

// handles querying for the announcement to delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $announcementID = $_POST['announcement_id'];

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
    <!-- <link rel="stylesheet" href="../../assets/css/style.css"> -->
    <link rel="stylesheet" href="css/dashPages.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>Greenwater Village Dashboard | Delete Announcement</title>
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
                    <h1>Delete Announcement</h1>
                    <p>Are you sure you want to delete this announcement?</p>
                    <form method="POST" action="delete_announcement.php">
                        <input type="hidden" name="announcement_id" value="<?= $announcement['announcement_id'] ?>">
                        <div class="delete-announcement-actions">
                            <button type="submit" name="delete-announcement">Delete</button>
                            <button type="submit" name="cancel">Cancel</button>
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
        </main>
        <footer class="main-footer">
            <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
        </footer>
        <!-- ending for main content -->
        </div> 
        <!-- ending for the lass wrapper -->
    </div>
        <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
    <style>
        /* Title */
        .dashboard-content h1 {
            color: #b00020;
            font-size: 26px;
            margin-bottom: 10px;
        }

        /* Confirmation message */
        .dashboard-content p {
            font-size: 16px;
            margin-bottom: 15px;
        }

        /* Action buttons */
        .delete-announcement-actions {
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
        }

        .delete-announcement-actions button {
            padding: 10px 20px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease-in-out;
        }

        .delete-announcement-actions button[name="delete-announcement"] {
            background-color: #c62828;
            color: #fff;
        }

        .delete-announcement-actions button[name="delete-announcement"]:hover {
            background-color: #b71c1c;
        }

        .delete-announcement-actions button[name="cancel"] {
            background-color: #b0bec5;
            color: #2e5e4d;
        }

        .delete-announcement-actions button[name="cancel"]:hover {
            background-color: #90a4ae;
        }

        /* Announcement preview */
        .announcement-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 25px 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
            border-left: 5px solid #4caf50;
            margin-top: 10px;
        }

        .announcement-card h2 {
            font-size: 22px;
            color: #2e5e4d;
            margin-bottom: 10px;
        }

        .announcement-card p {
            font-size: 15px;
            margin: 8px 0;
            color: #444;
        }

        .announcement-card #badge {
            display: inline-block;
            background-color: #e0f2f1;
            color: #00695c;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
        }

        #announcementThumbnail {
            margin: 15px 0;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        #announcementBody {
            white-space: pre-wrap;
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 15px;
        }

        /* Attachments */
        .announcement-attachment {
            margin-top: 20px;
        }

        .announcement-attachment h2 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #2e5e4d;
        }

        .announcement-attachment a {
            color: #1e88e5;
            text-decoration: none;
            font-weight: 500;
        }

        .announcement-attachment a:hover {
            text-decoration: underline;
        }

        /* Responsive behavior */
        @media (max-width: 768px) {
            .dashboard-content {
                padding: 20px;
            }

            .announcement-card {
                padding: 20px;
            }

            .delete-announcement-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .delete-announcement-actions button {
                width: 100%;
            }
        }

    </style>
</body>

</html>
