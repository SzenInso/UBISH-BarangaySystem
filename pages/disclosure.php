<?php
    include '../config/dbconfig.php';
    session_start();

    if (isset($_POST['delete'])) {
        $announcement_id = $_POST['announcement_id'];

        $deleteQuery = "DELETE FROM announcements WHERE announcement_id = :announcement_id";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->execute([":announcement_id" => $announcement_id]);

        if ($deleteStmt) {
            echo "<script>alert('Announcement deleted successfully!'); window.location.href = 'disclosure.php';</script>";
        } else {
            echo "<script>alert('Failed to delete announcement. Please try again.'); window.location.href = 'disclosure.php';</script>";
        }
    }

    if (isset($_POST['edit'])) {
        $announcement_id = $_POST['announcement_id'];
        header("Location: edit_announcement.php?announcement_id=$announcement_id");
    }

    $fetchAnnouncements = "SELECT * FROM announcements";
    $announcements = $pdo->query($fetchAnnouncements)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Disclosure - Announcements</title>
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
        <div class="announcement-table">
            <h1>All Announcements</h1>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($announcements as $announcement) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($announcement['title']); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="announcement_id" value="<?php echo $announcement['announcement_id']; ?>">
                                <button type="submit" name="edit">Edit</button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="announcement_id" value="<?php echo $announcement['announcement_id']; ?>">
                                <button type="submit" name="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>
