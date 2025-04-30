<!-- PHP CODE -->
<?php
    include '../../config/dbconfig.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // redirects back to registration if not registered
    if (!isset($_SESSION['registration_emp_id'])) {
        header('location:../account/register.php');
        exit;
    }

    if (isset($_POST['signup'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($password !== $confirmPassword) {
            echo "<br><p><center>Passwords do not match.<center></p>";
        } else {
            $protectedPassword = password_hash($password, PASSWORD_DEFAULT);
            $signupQuery = "INSERT INTO login_registration (username, email, password) VALUES (:username, :email, :password)";
            $signup = $pdo->prepare($signupQuery);
            $signup->execute([
                ":username" => $username,
                ":email" => $email,
                ":password" => $protectedPassword
            ]);

            if ($signup) {
                $_SESSION['registration_login_id'] = $pdo->lastInsertId();
                header('location:../account/process_registration.php');
                exit;
            } else {
                echo "
                    <script>
                        Swal.fire({
                            title: 'Failed to sign up.',
                            text: 'Failed to create account. Please try again.',
                            icon: 'error'
                        }).then((result) => {
                            window.location.href = '../account/register.php'
                        });
                    </script>
                ";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>UBISH Dashboard | Sign Up</title>
    <script src="../../assets/js/sweetalert2.js"></script>
</head>
<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="../../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
                <h1>UBISH</h1>
            </div>
        </div>
        <hr>
    </header>
    <main>
        <form method="POST">
            <div class="signup-form">
                <h1>Enter Credentials For Logging In</h1>
                <div class="signup-credentials">
                    <p>Username</p>
                    <input type="text" name="username" placeholder="Enter username" required>
                </div>
                <div class="signup-credentials">
                    <p>Email Address</p>
                    <input type="email" name="email" placeholder="Enter email" required>
                </div>
                <div class="signup-credentials">
                    <p>Password</p>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>
                <div class="signup-credentials">
                    <p>Confirm Password</p>
                    <input type="password" name="confirmPassword" placeholder="Confirm entered password" required>
                </div>
                <button name="signup">Sign Up</button>
            </div>
        </form>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>