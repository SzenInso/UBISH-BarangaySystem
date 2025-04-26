<?php
    include '../../config/dbconfig.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        $query = "SELECT * FROM login_details WHERE username = :username";
        $login = $pdo->prepare($query);
        $login->execute([":username" => $username]);
        $activeUser = $login->fetch();
    
        if ($activeUser && password_verify($password, $activeUser['password'])) {
            $_SESSION['user_id'] = $activeUser['user_id'];
            $_SESSION['emp_id'] = $activeUser['emp_id'];
            $_SESSION['username'] = $activeUser['username'];
            echo "
                <script>
                    alert('Logged in successfully!');
                    window.location.href = '../main/dashboard.php';
                </script>
            ";
        } else {
            echo "<br><p><center>Invalid username or password.</center></p>";
        }
    }

    if (isset($_POST['cancel'])) {
        header('location:../../index.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Login Page</title>
</head>
<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="../../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
                <h1>UBISH</h1>
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="../account/register.php">Sign Up</a>
                    </li>
                </ul>
            </nav>
        </div>
        <hr>
    </header>
    <main>
        <form method="POST">
            <div class="login-form">
                <h1>Log In to UBISH</h1>
                <div class="login-credentials">
                    <p>Username</p>
                    <input type="text" name="username" placeholder="Enter username">
                </div>
                <div class="login-credentials">
                    <p>Password</p>
                    <input type="password" name="password" placeholder="Enter password">
                </div>
                <div class="login-btns">
                    <button name="login">Log In</button>
                    <button name="cancel">Cancel</button>
                </div>
            </div>
        </form>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>
