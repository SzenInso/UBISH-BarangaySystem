<?php
    include 'dbconfig.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('location:index.php');
        exit;
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header('location:index.php');   
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard</title>
</head>
<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="https://placehold.co/100" alt="UBISH Logo">
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
        <?php 
            $query = "SELECT * FROM employee_details WHERE emp_id = :emp_id";
            $empDetails = $pdo->prepare($query);
            $empDetails->execute([":emp_id" => $_SESSION['emp_id']]);
        ?>
        <div class="employee-dashboard">
            <h1><center>Barangay Dashboard</center></h1>
            <br>
        <?php 
            foreach ($empDetails as $row) {
        ?>
                <img 
                    src="<?php echo $row['picture']; ?>"
                    alt="Employee Picture"
                    style="
                        width: 200px;
                        border-radius: 50%; 
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto;
                    "
                >
                <br>
                <h2><center>Welcome, <?php echo $row['first_name']; ?>!</center></h2>
        <?php
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
