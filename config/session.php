<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header('location:../../index.php');
        exit;
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header('location:../../index.php');   
        exit;
    }
?>