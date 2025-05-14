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
    <title>UBISH | Homepage</title>
    <style>
        /*stylings for the dropdown */
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }

        nav ul li {
            position: relative;
        }

        nav ul li a {
            text-decoration: none;
            padding: 10px;
            display: block;
        }

        nav ul .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: white;
            min-width: 200px;
            z-index: 1000;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
        }

        nav ul .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
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
                    <li class="dropdown">
                        <a href="#">Services</a>
                        <ul class="dropdown-content">
                            <li><a href="pages/main/certificates.php">Certificate of Residence</a></li>
                            <li><a href="pages/main/permits.php">Barangay Permit</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="pages/account/login.php">Employee Portal</a>
                    </li>
                </ul>
            </nav>

        </div>
        <hr>
    </header>
    <main>
        <h1 id="homepage">
            <center>Homepage</center>
        </h1>
        <div class="dashboard-announcements">
            <?php
            if (count($publicAnnouncementDetails) < 1) {
                echo "<p><center>No announcements.</center></p>";
            } else {
                foreach ($publicAnnouncementDetails as $ann) {
                    ?>
                    <div class="announcement-card">
                        <h2><?php echo $ann['title']; ?></h2>
                        <p>
                            <strong>Issued By:</strong>&nbsp;<?php echo $ann['first_name'] . ' ' . $ann['last_name']; ?>
                            <i>(<?php echo $ann['username']; ?>)</i> |
                            <?php echo date("F j, Y g:i:s A", strtotime($ann['post_date'])); ?>
                        </p>
                        <p id="badge"><?php echo $ann['category'] ?></p><br>
                        <?php if (!empty($ann['thumbnail'])) { ?>
                            <img src="<?php echo str_replace('../', '', $ann['thumbnail']); ?>"
                                alt="thumbnail_<?php echo $ann['announcement_id']; ?>" id="announcementThumbnail">
                        <?php } ?>
                        <p id="announcementBody"><?php echo nl2br(htmlspecialchars($ann['body'])); ?></p>
                        <?php
                        $announcementAttachments = $attachmentsByAnnouncement[$ann['announcement_id']] ?? [];
                        if (!empty($announcementAttachments)) {
                            ?>
                            <div class="announcement-attachment">
                                <h2>Attachments:</h2>
                                <?php foreach ($announcementAttachments as $attach) { ?>
                                    <div>
                                        <a href="<?php echo htmlspecialchars(str_replace('../', '', $attach['file_path'])); ?>"
                                            target="_blank">
                                            <?php echo htmlspecialchars($attach['file_name']); ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
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