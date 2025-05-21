<?php include '../../baseURL.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UBISH | Certificate of Residency</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/index.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>partials/partials.css">
    <link rel="stylesheet" href="css/residency.css">
</head>
<body>

<?php include '../../partials/header.php';?>

<main class="main-content residency-wrapper">
    <section class="residency-info">
    <h2>Requirements for Certificate of Residency</h2>
    <hr>
    <p>The Certificate of Residency is an official document required for various government and private transactions. This certificate confirms your residency within the barangay and may be used for applying for business permits, IDs, bank accounts, and more.</p>
    
    <h3>Procedure:</h3>
    <ol>
        <li>Click the <strong>Request Certificate of Residency</strong> button. Fill out and submit the form.</li>
        <li>Prepare your <strong>Cedula</strong> from your local city or municipal hall.</li>
        <li>Prepare at least <strong>100 pesos</strong> for processing.</li>
        <li>Wait for the confirmation message from the barangay.</li>
        <li>The document will be sealed and handed over after payment.</li>
    </ol>
    <div class="request-box">
        <button id="openRequestBtn" class="request-btn">Request Certificate of Residency</button>
    </div>

    <hr>
    <h3>Barangay Clearance Requirements:</h3>
    <ol>
        <li>Cedula (Community Tax Certificate)</li>
        <li>Processing fee (less than 100 pesos)</li>
        <li>Valid ID (for verification)</li>
    </ol>
    <p class="note"><em>Note: After filling out the <strong>Request Certificate of Residency</strong> form, please bring the required documents to the barangay hall.</em></p>

    <hr>
    <h3>Common Uses of Barangay Clearance:</h3>
    <ol>
        <li>Job application</li>
        <li>Business permit</li>
        <li>Postal ID application</li>
        <li>Police/NBI clearance</li>
        <li>Travel abroad requirements</li>
        <li>License renewals</li>
        <li>Loan or financing applications</li>
        <li>Utility service applications</li>
    </ol>
    </section>

    <?php include 'request_residency.php';?>
</main>

<script src="<?= BASE_URL ?>assets/js/sweetalert2.js"></script>

<?php include '../../partials/footer.php';?>

</body>
</html>
