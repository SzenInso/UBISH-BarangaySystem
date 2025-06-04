<?php
include 'config/dbconfig.php';
include 'config/dbfetch_public.php';
include 'baseURL.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header('location:pages/main/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="css/index.css" />
    <title>UBISH | Barangay Greenwater Village</title>
</head>

<body>
    <?php include 'partials/header.php'; ?>
    <main class="main-content">
        <!-- Main Content -->
        <section class="hero-section">
            <div class="hero-text">
                <h1>Welcome to Barangay Greenwater Village</h1>
                <p>
                    Nestled in the heart of Baguio City, Greenwater Village is a vibrant community dedicated to transparency, service, and civic engagement. This official portal provides residents and visitors with access to public announcements, services, and barangay initiatives.
                </p>
                <p>
                    Stay informed. Stay involved. Together, we build a better barangay.
                </p>
            </div>
        </section>

        <!-- Announcements Section -->
        <section class="announcements-section">
            <h2 class="section-title">Latest Announcements</h2>
            <div class="announcement-container">
                <?php
                if (count($publicAnnouncementDetails) < 1) {
                    echo "<p class='no-announcement'>No announcements available at this time.</p>";
                } else {
                    foreach ($publicAnnouncementDetails as $ann) {
                ?>
                    <div class="announcement-card">
                        <h3><?php echo htmlspecialchars($ann['title']); ?></h3>
                        <p class="meta">
                            Issued by <strong><?php echo $ann['first_name'] . ' ' . $ann['last_name']; ?></strong>
                            <i>(<?php echo $ann['username']; ?>)</i> |
                            <?php echo date("F j, Y g:i A", strtotime($ann['post_date'])); ?>
                        </p>
                        <span class="badge"><?php echo htmlspecialchars($ann['category']); ?></span>
                        <?php if (!empty($ann['thumbnail'])) { ?>
                            <img src="<?php echo str_replace('../', '', $ann['thumbnail']); ?>" alt="thumbnail" class="announcement-thumbnail">
                        <?php } ?>
                        <p class="announcement-body"><?php echo nl2br(htmlspecialchars($ann['body'])); ?></p>
                        <?php
                        $announcementAttachments = $attachmentsByAnnouncement[$ann['announcement_id']] ?? [];
                        if (!empty($announcementAttachments)) {
                            echo "<div class='announcement-attachment'><strong>Attachments:</strong><br>";
                            foreach ($announcementAttachments as $attach) {
                                echo "<a href='" . htmlspecialchars(str_replace('../', '', $attach['file_path'])) . "' target='_blank'>" . htmlspecialchars($attach['file_name']) . "</a><br>";
                            }
                            echo "</div>";
                        }
                        ?>
                    </div>
                <?php
                    }
                }
                ?>
            </div>
        </section>
    </main>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
