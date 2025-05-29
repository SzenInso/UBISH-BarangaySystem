<?php
    include '../../config/dbconfig.php';
    include '../../baseURL.php';
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
    <title>UBISH Dashboard | Forgot Password</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/index.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/login.css">

    <script src="../../assets/js/sweetalert2.js"></script>
</head>
<body>
    <?php include '../../partials/header.php'; ?>

    <main>
        <div class="forgot-password-container login-form">
            <form method="POST" class="forgot-password-form">
                <h1>Forgot Password?</h1>
                <p>You can reset your password by entering your username, selecting your security question, and providing the correct answer.</p>

                <div class="login-credentials">
                    <label for="username">Username</label>
                    <input
                        id="username"
                        type="text"
                        name="username"
                        placeholder="Enter username"
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                        required
                    >
                </div>

                <div class="security-question">
                    <label for="security-question">Security Question</label>
                    <select id="security-question" name="security-question" required>
                        <option value="" disabled <?php if(!isset($_POST['security-question'])) echo 'selected'; ?>>Select your security question</option>
                        <?php foreach ($questions as $q): ?>
                            <option
                                value="<?php echo htmlspecialchars($q); ?>"
                                <?php if (isset($_POST['security-question']) && $_POST['security-question'] === $q) echo 'selected'; ?>
                            >
                                <?php echo htmlspecialchars($q); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="login-credentials">
                    <label for="answer">Answer</label>
                    <input
                        id="answer"
                        type="text"
                        name="answer"
                        placeholder="Enter your answer"
                        autocomplete="off"
                        required
                        value="<?php echo htmlspecialchars($_POST['answer'] ?? ''); ?>"
                    >
                </div>

                <button type="submit" name="submit-answer" class="submit-btn">Submit Answer</button>
            </form><br>
            <a href="../account/login.php" class="back-link">← Go Back to Log In</a>
        </div>
    </main>

    <?php include '../../partials/footer.php'; ?>
</body>
<style>
/* html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}
body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
    /* Center the container vertically and horizontally */
/* main {
    flex: 1;
    min-height: 110vh; /* adjust if you have header or footer */ /*
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    background-color: #f0f5f2;
}  */

/* Container box */
.forgot-password-container {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(46, 139, 87, 0.15);
    padding: 40px 30px;
    max-width: 400px;
    width: 100%;
    box-sizing: border-box;
    text-align: center;
    transition: box-shadow 0.3s ease;
    border: 5px solid #2E8B57;
}

/* Form heading and text */
.forgot-password-form h1 {
    color: #2E8B57;
    font-weight: 700;
    font-size: 28px;
    margin-bottom: 15px;
}

.forgot-password-form p {
    font-size: 16px;
    margin-bottom: 25px;
    color: #333;
    line-height: 1.4;
}

/* Back to login link */
.back-link {
    display: inline-block;
    margin-bottom: 25px;
    color: #2E8B57;
    font-weight: 600;
    text-decoration: none;
    font-size: 14px;
}

.back-link:hover {
    text-decoration: underline;
}

/* Labels */
.forgot-password-form label {
    display: block;
    font-weight: 600;
    color: #333;
    text-align: left;
    margin-bottom: 6px;
    font-size: 14px;
}

/* Inputs and select */
.forgot-password-form input[type="text"],
.forgot-password-form select {
    width: 100%;
    padding: 12px 14px;
    font-size: 16px;
    border: 2px solid #ccc;
    border-radius: 8px;
    margin-bottom: 20px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

.forgot-password-form input[type="text"]:focus,
.forgot-password-form select:focus {
    border-color: #2E8B57;
    outline: none;
}

/* Submit button */
.submit-btn {
    background-color: #2E8B57;
    border: none;
    color: white;
    font-weight: 700;
    font-size: 16px;
    padding: 14px 0;
    width: 100%;
    border-radius: 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #276946;
}

/* Responsive */
@media (max-width: 600px) {
    .forgot-password-container {
        padding: 30px 20px;
    }
}

</style>
</html>
