<?php
    include '../../config/dbconfig.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // redirects back to registration if error in processing
    if (!isset($_SESSION['registration_emp_id']) || !isset($_SESSION['registration_login_id'])) {
        session_unset();
        session_destroy();
        echo "
            <link rel='stylesheet' href='../../assets/css/style.css'>
            <script src='../../assets/js/sweetalert2.js'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Failed to register.',
                        text: 'Failed to process registration. Please try again.',
                        icon: 'error'
                    }).then(() => {
                        window.location.href = '../account/register.php';
                    });
                });
            </script>
        ";
    }

    $forApprovalQuery = "INSERT INTO registration (registration_emp_id, registration_login_id, status) VALUES (:registration_emp_id, :registration_login_id, 'Pending')";
    $forApproval = $pdo->prepare($forApprovalQuery);
    $forApproval->execute([
        ":registration_emp_id" => $_SESSION['registration_emp_id'],
        ":registration_login_id" => $_SESSION['registration_login_id']
    ]);

    if ($forApproval) {
        unset($_SESSION['registration_emp_id']);
        unset($_SESSION['registration_login_id']);
        session_unset();
        session_write_close();
        echo "
            <link rel='stylesheet' href='../../assets/css/style.css'>
            <script src='../../assets/js/sweetalert2.js'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Account registration requested.',
                        text: 'The registered account is now requested for approval. Please wait in logging in until your account is approved.',
                        icon: 'info'
                    }).then(() => {
                        window.location.href = '../account/login.php';
                    });
                });
            </script>
        ";
    } else {
        unset($_SESSION['registration_emp_id']);
        unset($_SESSION['registration_login_id']);
        session_unset();
        session_destroy();
        echo "
            <link rel='stylesheet' href='../../assets/css/style.css'>
            <script src='../../assets/js/sweetalert2.js'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Failed to register.',
                        text: 'Failed to submit registration. Please try again.',
                        icon: 'error'
                    }).then(() => {
                        window.location.href = '../account/register.php';
                    });
                });
            </script>
        ";
    }
?>