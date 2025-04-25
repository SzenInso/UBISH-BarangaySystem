<?php
    include 'config/dbconfig.php';
    include 'config/dbfetch_public.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // redirect if user is already logged in
    if (isset($_SESSION['user_id'])) {
        header('location:pages/main/dashboard.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Homepage</title>
</head>
<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
                <h1>UBISH</h1>
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="pages/account/login.php">Log In</a>
                    </li>
                    <li>
                        <a href="pages/account/register.php">Sign Up</a>
                    </li>
                </ul>
            </nav>
        </div>
        <hr>
    </header>
    <main>
        <?php echo "<center><h1>Homepage</h1></center>" ?>
        <style>
            img#announcementThumbnail {
                aspect-ratio: 3 / 2;
                width: 100%;
                max-width: 300px;
                overflow: hidden;
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
                if (count($publicAnnouncementDetails) < 1) {
                    echo "<p><center>No announcements.</center></p>";
                } else {
                    foreach ($publicAnnouncementDetails as $ann) {
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
                                    <img src="<?php echo str_replace('../', '', $ann['thumbnail']); ?>" alt="thumbnail_<?php echo $ann['announcement_id']; ?>" id="announcementThumbnail">
                            <?php
                                }
                            ?>
                            <p><?php echo nl2br(htmlspecialchars($ann['body'])); ?></p>

                        </div>
            <?php
                    }
            }
            ?>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>