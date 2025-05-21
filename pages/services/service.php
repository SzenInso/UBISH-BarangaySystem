<?php include '../../baseURL.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/index.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>partials/partials.css">
    <link rel="stylesheet" href="css/service.css">

    <title>UBISH | Services</title>
</head>
<body>

    <?php include '../../partials/header.php';?>

    <main class="main-content services-content">
        <div class="services-container">
            <h2>SERVICES WE CURRENTLY OFFER:</h2>
            <p>To start transaction, tap or click a service below:</p>

            <div class="service-boxes">
                <a href="residencyCert.php" class="service-box">
                    <h3>Certificate of Residency</h3>
                </a>
                <a href="clearanceCert.php" class="service-box">
                    <h3>Barangay Clearance</h3>
                </a>
                <a href="#" class="service-box">
                    <h3>Good Moral</h3>
                </a>
                <!-- Future services -->
<!--                 
                <a href="#" class="service-box">
                    <h3>Barangay Certificate of Low Income</h3>
                </a>
                <a href="#" class="service-box">
                    <h3>Affidavit of Solo Parent</h3>
                </a>
                
                <a href="#" class="service-box">
                    <h3>Blotter Report</h3>
                </a>
                -->
            </div>
        </div>
    </main>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .services-container {
            text-align: center;
        }
    </style>
    <?php include '../../partials/footer.php';?>

</body>
</html>
