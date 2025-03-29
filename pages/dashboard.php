<?php
    include '../config/dbconfig.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('location:../index.php');
        exit;
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header('location:../index.php');   
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>UBISH Dashboard | Home</title>
</head>
<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
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
                    <li class="active"><a href="../pages/dashboard.php">Home</a></li>
                    <li><a href="../pages/account.php">Account</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1><center>Barangay Dashboard</center></h1><br>
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
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>
