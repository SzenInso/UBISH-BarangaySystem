<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Homepage</title>
</head>
<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="https://placehold.co/100" alt="UBISH Logo">
                <h1>UBISH</h1>
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="login.php">Log In</a>
                    </li>
                    <li>
                        <a href="register.php">Sign Up</a>
                    </li>
                </ul>
            </nav>
        </div>
        <hr>
    </header>
    <main>
        <?php echo "<center><h1>Homepage</h1></center>" ?>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>