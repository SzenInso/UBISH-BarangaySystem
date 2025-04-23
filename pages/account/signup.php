<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Sign Up Page</title>
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
        <?php 
            include '../../config/dbconfig.php';
            $fetchQuery = "SELECT emp_id FROM employee_details WHERE emp_id = :emp_id";
            $fetchEmpId = $pdo->prepare($fetchQuery);
            $fetchEmpId->execute([":emp_id" => htmlspecialchars($_GET['emp_id'])]);
        ?>
        <form method="POST">
            <div class="signup-form">
                <h1>Enter Credentials For Logging In</h1>
            <?php
                if ($fetchEmpId->rowCount() < 0) {
                    echo "<br><p><center>Employee ID not found.<center></p>";
                    echo "<button><a href='pages/account/register.php'>Go Back</a></button>";
                } else {
                    $empId = $fetchEmpId->fetchColumn();
            ?>
                    <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($empId); ?>">
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
            <?php
                }
            ?>
            </div>
        </form>
        <!-- PHP CODE -->
        <?php
            if (isset($_POST['signup'])) {
                $emp_id = $_POST['emp_id'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $confirmPassword = $_POST['confirmPassword'];

                if ($password !== $confirmPassword) {
                    echo "<br><p><center>Passwords do not match.<center></p>";
                } else {
                    $protectedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $query = "INSERT INTO login_details (emp_id, username, email, password) VALUES (:emp_id, :username, :email, :password)";
                    $signup = $pdo->prepare($query);
                    $signup->execute([
                        ":emp_id" => $emp_id,
                        ":username" => $username,
                        ":email" => $email,
                        ":password" => $protectedPassword
                    ]);

                    if ($signup) {
                        echo "
                            <script>
                                alert('Account created successfully!');
                                window.location.href = '../account/login.php';
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Failed to create account. Please try again.');
                                window.location.href = '../account/register.php';
                            </script>
                        ";
                    }
                }
            }
        ?>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>