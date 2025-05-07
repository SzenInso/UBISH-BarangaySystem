<?php
    include '../../config/dbconfig.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>UBISH Dashboard | Login</title>
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
                <h1>Log In to UBISH</h1><br>
                <a href="../../index.php">← Go Home</a>
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
                </div>
                <br><a href="../account/forgot_password.php">Forgot Password?</a>
                <?php 
                    if (isset($_POST['login'])) {
                        $username = $_POST['username'];
                        $password = $_POST['password'];
                    
                        $query = "SELECT * FROM login_details WHERE username = :username";
                        $login = $pdo->prepare($query);
                        $login->execute([":username" => $username]);
                        $activeUser = $login->fetch();
                    
                        if ($activeUser && (password_verify($password, $activeUser['password']) || $activeUser['password'] == $password)) {
                            $_SESSION['user_id'] = $activeUser['user_id'];
                            $_SESSION['emp_id'] = $activeUser['emp_id'];
                            $_SESSION['username'] = $activeUser['username'];
                    ?>
                            <script>
                                Swal.fire({
                                    title: "Logged in successfully",
                                    icon: "success",
                                }).then((result) => {
                                    window.location.href = '../main/dashboard.php'
                                });
                            </script>
                    <?php } else { ?>
                            <script>
                                Swal.fire({
                                    title: "Invalid username or password.",
                                    icon: "error",
                                });
                            </script>
                    <?php }
                    }
                ?>
            </div>
        </form>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>