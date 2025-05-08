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
    <title>UBISH Dashboard | Forgot Account</title>
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
                        <a href="../account/login.php">Log In</a>
                    </li>
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
                <h1>Forgot Password?</h1><br>
                <p>You can request a password reset by placing your</p>
                <p>registered <b>username</b> or <b>email</b>.</p><br>
                <a href="../account/login.php">← Go Back to Log In</a>
                <div class="login-credentials">
                    <p>Username or Email</p>
                    <input type="text" name="user-email" placeholder="Enter username or email">
                </div>
                <div class="login-btns">
                    <button name="request-reset">Request Password Reset</button>
                </div>
                <?php 
                    if (isset($_POST['request-reset'])) {
                        $user_email = $_POST['user-email'];

                        $fetchEmpQuery = "SELECT * FROM login_details WHERE username = :username OR email = :email";
                        $fetchEmpStmt = $pdo->prepare($fetchEmpQuery);
                        $fetchEmpStmt->execute([
                            ":username" => $user_email,
                            ":email" => $user_email
                        ]);

                        if ($fetchEmpStmt->rowCount() < 1) {
                            echo "<br><p><center>Username or email is invalid or not registered.</center></p>";
                        } else {
                            $fetchEmp = $fetchEmpStmt->fetch(); 
                            
                            $user_id = $fetchEmp['user_id'];
                            $email = $fetchEmp['email'];
                            $token = bin2hex(random_bytes(32));
                            $expiry_date = date("Y-m-d H:i:s", strtotime("+24 hours"));

                            $pwdResetQuery = "
                                INSERT INTO password_resets (user_id, email, token, expiry_date, reset_status)
                                VALUES (:user_id, :email, :token, :expiry_date, 'Pending')
                            ";
                            $pwdReset = $pdo->prepare($pwdResetQuery);
                            $pwdReset->execute([
                                ":user_id" => $user_id,
                                ":email" => $email,
                                ":token" => $token,
                                ":expiry_date" => $expiry_date
                            ]);

                            if ($pwdReset) {
                                echo "
                                    <script>
                                        Swal.fire({
                                            title: 'Request successful.',
                                            html: `
                                                Your password reset has been requested. Please wait for your <b>reset approval</b>.<br>
                                                If your request has been approved, a password reset link will be sent to your registered email.
                                            `,
                                            icon: 'info'
                                        }).then((result) => {
                                            window.location.href = '../account/login.php'
                                        });
                                    </script>
                                ";
                            } else {
                                echo "
                                    <script>
                                        Swal.fire({
                                            title: 'Cannon request.',
                                            text: 'Your password reset request cannot process properly. Please try again.',
                                            icon: 'error'
                                        }).then((result) => {
                                            window.location.href = '../account/logins.php'
                                        });
                                    </script>
                                ";
                            }
                        }
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
