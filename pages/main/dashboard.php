<?php
    include '../../config/dbfetch.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>UBISH Dashboard | Home</title>
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
                    <li class="active"><a href="../main/dashboard.php">Home</a></li>
                    <li><a href="../main/account.php">Account</a></li>
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../main/documents.php">Documents</a></li>
                    <li><a href="../main/announcements.php">Post Announcement</a></li>
                    <h3>Tables & Requests</h3>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/employee_table.php">Employee Table</a></li>'; } ?>
                    <?php if ($accessLevel >= 3) { echo '<li><a href="../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/admin_certificate_requests.php">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="#">Permit Requests</a></li>'; } ?>
                    <h3>Reports</h3>
                    <li><a href="../main/incidents.php">Incident Reports</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1><center>Barangay Dashboard</center></h1><br>
                <div class="dashboard-greetings">
                    <?php 
                        $query = "SELECT * FROM employee_details WHERE emp_id = :emp_id";
                        $empDetails = $pdo->prepare($query);
                        $empDetails->execute([":emp_id" => $_SESSION['emp_id']]);

                        foreach ($empDetails as $row) {
                    ?>
                            <h2><center>Welcome, <?php echo $row['first_name']; ?>!</center></h2>
                    <?php
                        }
                    ?>
                </div>
                <br>
                <div class="dashboard-announcements">                  
                    <?php 
                        if ($announcementDetails->rowCount() < 1) { echo "<p><center>No announcements.</center></p>"; } else {
                            $announcements = [];

                            foreach ($announcementDetails as $row) {
                                $announcement_id = $row['announcement_id'];

                                if (!isset($announcements[$announcement_id])) {
                                    $announcements[$announcement_id] = [
                                        'announcement_id' => $row['announcement_id'],
                                        'title' => $row['title'],
                                        'body' => $row['body'],
                                        'category' => $row['category'],
                                        'thumbnail' => $row['thumbnail'],
                                        'post_date' => $row['post_date'],
                                        'first_name' => $row['first_name'],
                                        'last_name' => $row['last_name'],
                                        'username' => $row['username'],
                                        'last_updated' => $row['last_updated'],
                                        'attachments' => []
                                    ];
                                }

                                if (!empty($row['file_path'])) {
                                    $announcements[$announcement_id]['attachments'][] = [
                                        'file_name' => $row['file_name'],
                                        'file_path' => $row['file_path']
                                    ];
                                }
                            }

                            foreach ($announcements as $ann) {
                    ?>
                                <div class="announcement-card">
                                    <!-- title and menu -->
                                    <div class="announcement-card-wrapper">
                                        <h2><?php echo $ann['title']; ?></h2>
                                        <?php if ($accessLevel >= 2) { ?>
                                                <div class="announcement-menu">
                                                    <button class="kebab-btn"><p style="font-size: x-large;">⁝</p></button>
                                                    <div class="kebab-menu">
                                                        <form method="GET" action="edit_announcement.php">
                                                            <input type="hidden" name="announcement_id" value="<?php echo $ann['announcement_id']; ?>">
                                                            <button type="submit">Edit Announcement</button>
                                                        </form>
                                                        <form method="GET" action="delete_announcement.php">
                                                            <input type="hidden" name="announcement_id" value="<?php echo $ann['announcement_id']; ?>">
                                                            <button type="submit" style="color: crimson;">Delete Announcement</button>
                                                        </form>
                                                    </div>
                                                </div>
                                        <?php } ?>
                                    </div>
                                    <!-- announcement author & announcement date -->
                                    <p>
                                        <strong>Issued By:</strong>&nbsp;<?php echo $ann['first_name'] . ' ' . $ann['last_name']; ?> 
                                        <i>(<?php echo $ann['username']; ?>)</i> | 
                                        <?php echo date("F j, Y g:i:s A", strtotime($ann['post_date'])); ?>
                                        <?php if (!empty($ann['last_updated'])): ?>
                                            <span style="color: gray; font-style: italic;"> (edited: <?php echo date("F j, Y g:i:s A", strtotime($ann['last_updated'])); ?>)</span>
                                        <?php endif; ?>
                                    </p>    
                                    
                                    <!-- category badge -->
                                    <p id="badge"><?php echo $ann['category'] ?></p><br>      
                                    <!-- thumbnail -->                      
                                    <?php if (!empty($ann['thumbnail'])) { ?>
                                        <img src="<?php echo $ann['thumbnail']; ?>" alt="thumbnail_<?php echo $ann['announcement_id']; ?>" id="announcementThumbnail">
                                    <?php } ?>
                                    <!-- announcement body -->
                                    <p id="announcementBody"><?php echo nl2br(htmlspecialchars($ann['body'])); ?></p>
                                    <!-- announcement attachments -->
                                    <?php if (!empty($ann['attachments'])) { ?>
                                        <div class="announcement-attachment">
                                            <h2>Attachments:</h2>
                                            <?php foreach ($ann['attachments'] as $attachment) { ?>
                                                <a href="<?php echo $attachment['file_path']; ?>" target="_blank"><?php echo $attachment['file_name']; ?></a><br>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <input type="hidden" name="announcement_id" value="<?php echo $ann['announcement_id']; ?>">
                                </div>
                    <?php
                            }         
                        }
                    ?>
                </div>
            </div>
        </div>
        <script src="../../assets/js/announcementActions.js"></script>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>
