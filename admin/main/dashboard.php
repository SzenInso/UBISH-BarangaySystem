<?php
    include '../../config/dbfetch.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="css/dash.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <title>Greenwater Village | Dashboard</title>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <ul>
                <h2>
                    <div class="dashboard-greetings">
                        <?php 
                            $query = "SELECT * FROM employee_details WHERE emp_id = :emp_id";
                            $empDetails = $pdo->prepare($query);
                            $empDetails->execute([":emp_id" => $_SESSION['emp_id']]);
                            foreach ($empDetails as $row) {
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
                </h2> </br>
                <h3><i class="fas fa-home"></i> Home</h3>
                <li class="active"><a href="../main/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../main/account.php"><i class="fas fa-user"></i> Account</a></li>
                <li><a href="../main/account_creation.php"><i class="fas fa-user-plus"></i> Account Creation</a></li>

                <h3><i class="fas fa-folder-open"></i> Documents & Disclosure</h3>
                <li><a href="../main/documents.php"><i class="fas fa-file-alt"></i> Documents</a></li>
                <li><a href="../main/announcements.php"><i class="fas fa-bullhorn"></i> Post Announcement</a></li>

                <h3><i class="fas fa-table"></i> Tables & Requests</h3>
                <li><a href="../main/employee_table.php"><i class="fas fa-users"></i> Employee Table</a></li>
                <li><a href="../main/account_requests.php"><i class="fas fa-user-check"></i> Account Requests</a></li>
                <li><a href="certificates/certificates.php"><i class="fas fa-certificate"></i> Certificate Requests</a></li>

                <h3><i class="fas fa-chart-bar"></i> Reports</h3>
                <li><a href="../main/incident_table.php"><i class="fas fa-exclamation-circle"></i> Incident History</a></li>
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
                    <h1><center>Greenwater Village Dashboard</center></h1><br>
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
                                            'author_id' => $row['author_id'],
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
                                            <div class="announcement-menu">
                                                <?php if ((int)$_SESSION['user_id'] === (int)$ann['author_id']) { ?>
                                                    <button class="kebab-btn" title="Announcement Options"><p style="font-size: x-large;">⁝</p></button>
                                                <?php } ?>
                                                <div class="kebab-menu">
                                                    <form method="POST" action="edit_announcement.php">
                                                        <input type="hidden" name="announcement_id" value="<?php echo $ann['announcement_id']; ?>">
                                                        <button type="submit">Edit Announcement</button>
                                                    </form>
                                                    <form method="POST" action="delete_announcement.php">
                                                        <input type="hidden" name="announcement_id" value="<?php echo $ann['announcement_id']; ?>">
                                                        <button type="submit" style="color: crimson;">Delete Announcement</button>
                                                    </form>
                                                </div>
                                            </div>
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
            </main>
            <footer class="main-footer">
                <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
            </footer>
        </div>
    </div>
    <script src="../../assets/js/announcementActions.js"></script>
    
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>
