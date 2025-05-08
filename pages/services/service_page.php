<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UBISH Dashboard | Services Offered</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../services/assets/service_page.css">
    <script src="../../assets/js/sweetalert2.js"></script>
</head>
<body>

<header>
    <div class="navigation">
        <div class="logo">
            <img src="../../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
            <h1>UBISH | Services Offered</h1>
        </div>
        <nav>
            <ul>
                <li class="dropdown">
                    <a href="service_page.php">Services Offered</a>
                    <ul class="dropdown-content">
                        <li><a href="residency.php">Certificate of Residency</a></li>
                        <!-- <li><a href="barangay_clearance.php">Barangay Clearance</a></li> -->
                        <!-- <li><a href="blotter_report.php">Blotter Report</a></li> -->
                    </ul>
                </li>
                <li>
                    <a href="../account/login.php">Log In</a>
                </li>
                <li>
                    <a href="../account/signup.php">Sign Up</a>
                </li>
            </ul>
        </nav>
    </div>
    <hr>
</header>

<div class="services-container">
    <h2>SERVICES WE CURRENTLY OFFER:</h2>
    <p>To start transaction, tap or click Service below:</p>

    <div class="service-boxes">
        <a href="residency.php" class="service-box">
            <h3>Certificate of Residency</h3>
        </a>
        <!-- <a href="barangay_clearance.php" class="service-box">
            <h3>Barangay Clearance</h3>
        </a> -->
        <!-- <a href="blotter_report.php" class="service-box">
            <h3>Blotter Report</h3>
        </a> -->
    </div>
</div>

<footer>
    <hr>
    <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
</footer>

</body>
</html>
