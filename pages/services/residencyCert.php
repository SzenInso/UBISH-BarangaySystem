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
        <p>The Certificate of Residency is an official document required for various government and private transactions. This certificate confirms your residency within the barangay and may be used for applying for business permits, IDs, bank accounts, and more.</p>

        <h3>Barangay clearance requirements:</h3>
        <ol>
            <li>Cedula (Community Tax Certificate)</li>
            <li>Processing fee (less than 50 pesos)</li>
            <li>Completed application form (available at the barangay office)</li>
            <li>Valid ID (for verification)</li>
            <li>Purpose for obtaining the clearance</li>
        </ol>

        <h3>Common uses of Barangay Clearance:</h3>
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

        <h3>Procedure:</h3>
        <ol>
            <li>Get a Cedula from your local city or municipal hall.</li>
            <li>Prepare at least 100 pesos for processing.</li>
            <li>Visit the barangay hall and request a clearance form.</li>
            <li>Fill out and submit the form.</li>
            <li>Wait for processing (usually 10–15 minutes).</li>
            <li>Sign the document and provide a thumbprint.</li>
            <li>The document will be sealed and handed over after payment.</li>
        </ol>
    </section>

    <aside class="request-box">
        <button id="openRequestBtn" class="request-btn">Request Certificate of Residency</button>
    </aside>
    <?php include 'request_residency.php';?>
</main>

<?php if (isset($_GET['success']) && $_GET['success'] === 'request_submitted'): ?>
<script>
Swal.fire({
  title: 'Submitted!',
  text: 'Your Certificate of Residency request was sent successfully.',
  icon: 'success',
  confirmButtonText: 'OK'
});
</script>
<?php endif; ?>

<script src="js/residency.js"></script>
<script src="<?= BASE_URL ?>assets/js/sweetalert2.js"></script>

<?php include '../../partials/footer.php';?>

</body>
</html>
