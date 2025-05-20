<?php
    include '../../config/dbconfig.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // unset session on refresh
    if (!isset($_POST['submit-answer'])) {
        unset($_SESSION['username']);
    }

    $questions = [
        "What is your mother's maiden name?",
        "In what city or town did your parents meet?",
        "What city were you born in?",
        "What was your childhood best friend’s nickname?",
        "What was the name of your first pet?"
    ];

    if (isset($_POST['submit-answer'])) {
        $username = $_POST['username'] ?? '';
        $question = $_POST['security-question'] ?? '';
        $answer = $_POST['answer'] ?? '';
        
        // empty fields
        if (empty($username) || empty($question) || empty($answer)) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Error.',
                        text: 'Please fill in all fields.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
        } else {
            // fetch correct answer hash for the selected question and username
            $query = "
                SELECT SEC.answer FROM security_questions AS SEC
                JOIN employee_details AS EMP ON SEC.emp_id = EMP.emp_id
                JOIN login_details AS LOG ON EMP.emp_id = LOG.emp_id
                WHERE LOG.username = :username AND SEC.question = :question
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ":username" => $username, 
                ":question" => $question
            ]);
            $row = $stmt->fetch();

            if ($row && password_verify($answer, $row['answer'])) {
                $_SESSION['username'] = $username;
                header('Location: reset_password.php');
                exit;
            } else {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Incorrect.',
                            text: 'The question or answer is incorrect.',
                            icon: 'error',
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
                </ul>
            </nav>
        </div>
        <hr>
    </header>
    <main>
        <form method="POST">
            <div class="login-form">
                <h1>Forgot Password?</h1><br>
                <p>You can reset your password by entering</p>
                <p>your username, selecting your security question,</p>
                <p>and providing the correct answer.</p>
                <a href="../account/login.php">← Go Back to Log In</a>
                <div class="login-credentials">
                    <p>Username</p>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" placeholder="Enter username">
                </div>
                <style>
                    .security-question select,
                    .security-question input[type="text"] {
                        margin: 4px 0 8px;
                        padding: 6px;
                        width: 256px;
                        border: 1px solid #aaa;
                        border-radius: 4px;
                        box-sizing: border-box;
                        background: #fff;
                    }
                </style>
                <div class="security-question">
                    <p>Security Question</p>
                    <select name="security-question">
                        <option value="" disabled selected>Select your security question</option>
                        <?php foreach ($questions as $q): ?>
                            <option value="<?php echo htmlspecialchars($q); ?>" <?php if (isset($_POST['security-question']) && $_POST['security-question'] === $q) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($q); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                    <input type="text" name="answer" placeholder="Enter your answer" autocomplete="off" value="<?php echo htmlspecialchars($_POST['answer'] ?? ''); ?>"><br>
                    <button name="submit-answer">Submit Answer</button>
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
