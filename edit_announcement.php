<?php
    include '../config/dbconfig.php';
    session_start();

    if (!isset($_GET['announcement_id'])) {
        header("Location: disclosure.php");
        exit();
    }

    $announcement_id = $_GET['announcement_id'];

    $fetchAnnouncementQuery = "SELECT * FROM announcements WHERE announcement_id = :announcement_id";
    $announcementStmt = $pdo->prepare($fetchAnnouncementQuery);
    $announcementStmt->execute([":announcement_id" => $announcement_id]);

    $announcement = $announcementStmt->fetch();

    if (!$announcement) {
        echo "<script>alert('Announcement not found.'); window.location.href = 'disclosure.php';</script>";
        exit();
    }

    if (isset($_POST['update'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];

        $updateQuery = "UPDATE announcements SET title = :title, content = :content WHERE announcement_id = :announcement_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            ":title" => $title,
            ":content" => $content,
            ":announcement_id" => $announcement_id
        ]);

        if ($updateStmt) {
            echo "<script>alert('Announcement updated successfully!'); window.location.href = 'disclosure.php';</script>";
        } else {
            echo "<script>alert('Failed to update announcement. Please try again.');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Edit Announcement</title>
</head>
<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
                <h1>UBISH</h1>
            </div>
        </div>
        <hr>
    </header>
    <main>
        <div class="edit-announcement-form">
            <h1>Edit Announcement</h1>
            <form method="POST">
                <div class="form-group">
                    <label for="title">Announcement Title</label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($announcement['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="content">Announcement Content</label>
                    <textarea name="content" id="content" rows="5" required><?php echo htmlspecialchars($announcement['content']); ?></textarea>
                </div>
                <button type="submit" name="update">Update Announcement</button>
            </form>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>
