<?php
include '../../baseURL.php';
include '../../config/dbconfig.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/index.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/login.css">
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>UBISH Dashboard | Login</title>
</head>
<body>

<?php include '../../partials/header.php'; ?>

<main>
    <form method="POST">
        <div class="login-form">
            <h1>Log In to UBISH</h1><br>
            <div class="login-credentials">
                <p>Username</p>
                <input type="text" name="username" placeholder="Enter username" required />
            </div>
            <div class="login-credentials">
                <p>Password</p>
                <input type="password" name="password" placeholder="Enter password" required />
            </div>
            <div class="login-btns">
                <button name="login" type="submit">Log In</button>
            </div>
            <br />
            <a href="../../index.php">← Go Home</a>
            <br />
            <a href="../account/forgot_password.php">Forgot Password?</a>

            <?php 
            if (isset($_POST['login'])) {
                $username = trim($_POST['username']);
                $password = trim($_POST['password']);
            
                $query = "SELECT * FROM login_details WHERE username = :username";
                $login = $pdo->prepare($query);
                $login->execute([":username" => $username]);
                $activeUser = $login->fetch();
            
                if ($activeUser && (password_verify($password, $activeUser['password']) || $activeUser['password'] == $password)) {
                    $_SESSION['user_id'] = $activeUser['user_id'];
                    $_SESSION['emp_id'] = $activeUser['emp_id'];
                    $_SESSION['username'] = $activeUser['username'];

                    $adminQuery = "SELECT access_level, legislature FROM employee_details WHERE emp_id = :emp_id";
                    $adminStmt = $pdo->prepare($adminQuery);
                    $adminStmt->execute([":emp_id" => $_SESSION['emp_id']]);
                    $admin = $adminStmt->fetch();
                    $isAdmin = ($admin['access_level'] == 4 && $admin['legislature'] === "Administrator");

                    if ($isAdmin) {
                        echo "
                            <script>
                                Swal.fire({
                                    title: 'Logged in successfully.',
                                    text: 'Logged in as administrator.',
                                    icon: 'success',
                                }).then(() => {
                                    window.location.href = '../../admin/main/dashboard.php';
                                });
                            </script>
                        ";
                        exit;
                    } else {
                        echo "
                            <script>
                                Swal.fire({
                                    title: 'Logged in successfully.',
                                    icon: 'success',
                                }).then(() => {
                                    window.location.href = '../../admin/main/dashboard.php';
                                });
                            </script>
                        ";
                        exit;
                    }
                } else { ?>
                    <script>
                        Swal.fire({
                            title: "Invalid username or password.",
                            icon: "error",
                        });
                    </script>
            <?php }
            } ?>
        </div>
    </form>
</main>

<?php include '../../partials/footer.php'; ?>

</body>
</html>
