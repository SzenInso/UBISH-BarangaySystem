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
            <script>
                alert('Failed to process registration. Please try again.');
                window.location.href = '../account/register.php';
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
            <script>
                alert('Registration submitted for approval.');
                window.location.href = '../account/login.php';
            </script>
        ";
    } else {
        unset($_SESSION['registration_emp_id']);
        unset($_SESSION['registration_login_id']);
        session_unset();
        session_destroy();
        echo "
            <script>
                alert('Failed to submit registration. Please try again.');
                window.location.href = '../account/register.php';
            </script>
        ";
    }
?>