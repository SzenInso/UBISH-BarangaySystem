<?php
    include '../config/dbconfig.php';

    $query = "SELECT title, views FROM announcements";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $announcements = $stmt->fetchAll();

    $titles = [];
    $views = [];

    foreach ($announcements as $announcement) {
        $titles[] = $announcement['title'];
        $views[] = $announcement['views'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphical Representation of Data</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
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
        <div class="chart-container">
            </script>
        </div>
    </main>

    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>
