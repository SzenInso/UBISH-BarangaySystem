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
    <link rel="stylesheet" href="assets/css/index.css" />
    <link rel="stylesheet" href="partials/partials.css" />
    <title>UBISH | Homepage</title>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <main class="main-content">
        <section class="welcome-section">
            <h2 class="welcome-heading">Welcome to Barangay Greenwater Village — Your Community, Your Home</h2>
            <p class="welcome-text">
                Nestled in the heart of Baguio City, Barangay Greenwater Village is more than just a neighborhood—it’s a thriving community built on unity, progress, and shared responsibility.
                Whether you're a resident, a visitor, or simply someone interested in learning more about our barangay, this website serves as your gateway to important updates, services, and community initiatives.
            </p>
            <p class="welcome-text">
                Stay informed with announcements, explore public services, and engage in our collective effort to foster a cleaner, safer, and more vibrant barangay.
                Together, we shape a community we are proud to call home!
            </p>
        </section>

        <div class="dashboard-announcements">
            <?php
            if (count($publicAnnouncementDetails) < 1) {
                echo "<p class='no-announcement'>No announcements.</p>";
            } else {
                foreach ($publicAnnouncementDetails as $ann) {
                    ?>
                    <div class="announcement-card">
                        <h2><?php echo $ann['title']; ?></h2>
                        <p><strong>Issued By:</strong>
                            <?php echo $ann['first_name'] . ' ' . $ann['last_name']; ?>
                            <i>(<?php echo $ann['username']; ?>)</i> |
                            <?php echo date("F j, Y g:i:s A", strtotime($ann['post_date'])); ?>
                        </p>
                        <p class="badge"><?php echo $ann['category']; ?></p>
                        <?php if (!empty($ann['thumbnail'])) { ?>
                            <img src="<?php echo str_replace('../', '', $ann['thumbnail']); ?>" alt="thumbnail_<?php echo $ann['announcement_id']; ?>" class="announcement-thumbnail">
                        <?php } ?>
                        <p class="announcement-body"><?php echo nl2br(htmlspecialchars($ann['body'])); ?></p>
                        <?php
                        $announcementAttachments = $attachmentsByAnnouncement[$ann['announcement_id']] ?? [];
                        if (!empty($announcementAttachments)) {
                            ?>
                            <div class="announcement-attachment">
                                <h3>Attachments:</h3>
                                <?php foreach ($announcementAttachments as $attach) { ?>
                                    <a href="<?php echo htmlspecialchars(str_replace('../', '', $attach['file_path'])); ?>" target="_blank">
                                        <?php echo htmlspecialchars($attach['file_name']); ?>
                                    </a><br>
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

    <?php include 'partials/footer.php'; ?>
</body>
</html>
