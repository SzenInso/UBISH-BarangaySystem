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
                    <li class="active"><a href="../main/dashboard.php">Home</a></li>
                    <li><a href="../main/account.php">Account</a></li>
                    <?php
                        // placeholder access control pages
                        if ($accessLevel >= 1) {
                            echo '<li><a href="#">Documents</a></li>';
                            echo '<li><a href="../main/announcements.php">Post Announcement</a></li>';
                        }
                        if ($accessLevel >= 2) {
                            echo '<li><a href="../main/employee_table.php">Employee Table</a></li>';
                        }
                        if ($accessLevel >= 3) {
                            echo '<li><a href="#">Edit Requests</a></li>';
                        }
                    ?>
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
                <!-- initial styles -->
                <style>
                    img#announcementThumbnail {
                        max-width: 400px;
                        max-height: 300px;
                        object-fit: cover;
                    }
                    p#badge {
                        display: inline-block;
                        padding: 0.25em 0.6em;
                        font-size: 0.75rem;
                        font-weight: bold;
                        background-color: lightgray;
                        border-radius: 999px;
                        text-align: center;
                        vertical-align: middle;
                        white-space: nowrap;
                    }
                </style>
                <div class="dashboard-announcements">
                    <?php 
                        if ($announcementDetails->rowCount() < 1) {
                            echo "<p><center>No announcements.</center></p>";
                        } else {
                            foreach ($announcementDetails as $ann) {
                    ?>
                                <div class="announcement-card" style="border: 1px solid red;">
                                    <h2><?php echo $ann['title']; ?></h2>
                                    <p>
                                        <strong>Issued By:</strong>&nbsp;<?php echo $ann['first_name'] . ' ' . $ann['last_name']; ?> 
                                        <i>(<?php echo $ann['username']; ?>)</i>
                                    </p>
                                    <p><?php echo date("F j, Y g:i:s A", strtotime($ann['post_date'])); ?></p>
                                    <p id="badge"><?php echo $ann['category'] ?></p><br>
                                    <?php 
                                        if (!empty($ann['thumbnail'])) {
                                    ?>
                                            <img src="<?php echo $ann['thumbnail']; ?>" alt="thumbnail_<?php echo $ann['announcement_id']; ?>" id="announcementThumbnail">
                                    <?php
                                        }
                                    ?>
                                    <p><?php echo nl2br(htmlspecialchars($ann['body'])); ?></p>
                                    <?php
                                        if (!empty($ann['file_path'])) {
                                            echo '<a href="'. $ann['file_path'] . '" target="_blank">' . $ann['file_name'] . '</a>';
                                        }
                                    ?>
                                </div>
                    <?php
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
