<?php 
    include '../config/dbconfig.php';
    include '../config/session.php';

    // fetches access control
    $accessQuery = "SELECT access_level FROM employee_details WHERE emp_id = :emp_id";
    $access = $pdo->prepare($accessQuery);
    $access->execute([":emp_id" => $_SESSION['emp_id']]);
    $accessLevel = $access->fetchColumn();

    // fetches single employee details
    $empQuery = "SELECT * FROM employee_details WHERE emp_id = :emp_id";
    $empDetails = $pdo->prepare($empQuery);
    $empDetails->execute([":emp_id" => $_SESSION['emp_id']]);

    // fetches all employee details
    $empAllQuery = "SELECT * FROM employee_details";
    $empAllDetails = $pdo->query($empAllQuery);
?>