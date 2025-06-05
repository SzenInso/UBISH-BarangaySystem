<?php
    include '../../config/dbconfig.php';
    include '../../baseURL.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['username'])) {
        header('location:../account/login.php');
        exit;
    }

    if (isset($_POST['reset-password'])) {
        $newPassword = $_POST['new-password'] ?? null;
        $confirmPassword = $_POST['confirm-password'] ?? null;

        if (empty($newPassword) || empty($confirmPassword)) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Missing values.',
                        text: 'Please fill in all the necessary fields.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
        } elseif ($newPassword !== $confirmPassword) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Incorrect values.',
                        text: 'Passwords do not match.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
        } else {
            try {
                $pdo->beginTransaction();

                $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
                $resetPasswordQuery = "UPDATE login_details SET password = :password WHERE username = :username";
                $resetPassword = $pdo->prepare($resetPasswordQuery);
                $resetPassword->execute([
                    ":password" => $newPasswordHashed,
                    ":username" => $_SESSION['username']
                ]);

                $isReset = $pdo->commit();
                if ($isReset) {
                    echo "
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                Swal.fire({
                                    title: 'Password reset successful.',
                                    text: 'Your password has been reset successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    window.location.href = '../account/login.php';
                                });
                            });
                        </script>
                    ";
                }
            } catch (Exception $e) {
                $pdo->rollBack();
                if (isset($_SESSION['security_question'])) { unset($_SESSION['security_question']); }
                if (isset($_SESSION['security_answer'])) { unset($_SESSION['security_answer']); }
                if (isset($_SESSION['username'])) { unset($_SESSION['username']); }
                
                error_log("An error occurred: " . $e->getMessage());
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Password reset error.',
                            text: 'An error occurred while resetting your password. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            window.location.href = '../account/login.php'
                        });
                    });
                </script>";
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
    <link rel="stylesheet" href="<?= BASE_URL ?>css/index.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/login.css">
    <link rel="icon" type="image/x-icon" href="assets/img/GreenwaterLogo.jpg">
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>Greenwater Village | Reset Password</title>
</head>
<body>

    <?php include '../../partials/header.php'; ?>
    <main>
    <div class="forgot-password-container login-form">
        <form method="POST" class="forgot-password-form">
            <h1>Reset Password</h1>
            <p>Reset password for: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>

            <div class="login-credentials">
                <label for="new-password">New Password</label>
                <input
                    id="new-password"
                    type="password"
                    name="new-password"
                    placeholder="Enter a new password"
                    required
                >
            </div>

            <div class="login-credentials">
                <label for="confirm-password">Confirm New Password</label>
                <input
                    id="confirm-password"
                    type="password"
                    name="confirm-password"
                    placeholder="Confirm the new password"
                    required
                >
            </div>

            <button type="submit" name="reset-password" class="submit-btn">Reset Password</button>
        </form><br>
        <a href="../account/login.php" class="back-link">← Go Back to Log In</a>
    </div>
</main>

    <?php include '../../partials/footer.php'; ?>
</body>
</html>
