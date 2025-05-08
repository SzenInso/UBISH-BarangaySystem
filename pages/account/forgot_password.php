<?php
    include '../../config/dbconfig.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // unsets session every refresh
    if (!isset($_POST['search']) && !isset($_POST['submit-answer'])) {
        unset($_SESSION['security_question']);
        unset($_SESSION['security_answer']);
        unset($_SESSION['username']);
    }

    if (isset($_POST['search'])) {
        $username = $_POST['username'];

        if (empty($username)) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please enter a username.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
        } else {
            try {
                $forgotPwdQuery = "
                    SELECT SEC.question, SEC.answer FROM security_questions AS SEC
                    JOIN employee_details AS EMP ON SEC.emp_id = EMP.emp_id
                    JOIN login_details AS LOG ON EMP.emp_id = LOG.emp_id
                    WHERE LOG.username = :username
                ";
                $forgotPwdStmt = $pdo->prepare($forgotPwdQuery);
                $forgotPwdStmt->execute([':username' => $username]);
                $forgotPwd = $forgotPwdStmt->fetch();

                if ($forgotPwd) {
                    $_SESSION['security_question'] = $forgotPwd['question'];
                    $_SESSION['security_answer'] = $forgotPwd['answer'];
                    $_SESSION['username'] = $username;
                } else {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                title: 'No security question.',
                                html: `
                                    No security question found for the entered username. <br>
                                    Please contact the administrator to reset password.
                                `,
                                icon: 'info',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                window.location.href = '../account/login.php'
                            });
                        });
                    </script>";
                }
            } catch (Exception $e) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Error.',
                            text: 'An error occurred while fetching the security question. Please try again.',
                            icon: 'info',
                            confirmButtonText: 'OK'
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
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>UBISH Dashboard | Forgot Password</title>
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
                <p>You can reset your password by placing</p>
                <p>your registered username and answering the</p>
                <p>security question you had set.</p><br>
                <a href="../account/login.php">← Go Back to Log In</a>
                <div class="login-credentials">
                    <p>Username</p>
                    <input type="text" name="username" value="<?php echo (!empty($_SESSION['username'])) ? $_SESSION['username'] : ''; ?>" placeholder="Enter username">
                </div>
                <div class="login-btns">
                    <button name="search">Search Account</button>
                </div>
                <br>
                <style>
                    .security-question h3,
                    .security-question p,
                    .security-question input {
                        margin-bottom: 8px;
                    }
                    .security-question button {
                        margin-top: 8px;
                    }
                    .security-question input {
                        padding: 4px;
                        width: 256px;
                    }
                </style>
                <div class="security-question">
                    <?php if (isset($_SESSION['security_question'])) { ?>
                        <h3>Answer the Security Question</h3>
                        <p><?php echo htmlspecialchars($_SESSION['security_question']); ?></p>
                        <input type="text" name="answer" placeholder="Enter your answer" autocomplete="off"><br>
                        <button name="submit-answer">Submit Answer</button>
                    <?php } ?>
                </div>
            </div>
            <?php 
                if (isset($_POST['submit-answer'])) {
                    $answer = $_POST['answer'];

                    if (empty($answer)) {
                        echo "<script>
                            document.addEventListener('DOMContentLoaded', function () {
                                Swal.fire({
                                    title: 'Missing Answer',
                                    text: 'Please enter an answer to the security question.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });
                        </script>";
                    } else {
                        // Check if the session variable for the hashed answer is set
                        if (isset($_SESSION['security_answer'])) {
                            // Verify the answer
                            if (password_verify($answer, $_SESSION['security_answer'])) {
                                // Redirect to reset_password.php
                                header('Location: reset_password.php');
                                exit;
                            } else {
                                echo "<script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        Swal.fire({
                                            title: 'Incorrect Answer',
                                            text: 'The answer you provided is incorrect.',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        });
                                    });
                                </script>";
                            }
                        } else {
                            echo "<script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Please search for your account first.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                });
                            </script>";
                        }
                    }
                }
            ?>
        </form>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>
