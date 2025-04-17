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

    // fetches announcements (debug)
    $announcementQuery = "
        SELECT A.*, ANN.*, L.username, E.first_name, E.middle_name, E.last_name FROM announcements AS A
        JOIN login_details AS L ON A.author_id = L.user_id
        JOIN employee_details AS E ON L.emp_id = E.emp_id
        LEFT JOIN attachments AS ANN ON A.announcement_id = ANN.announcement_id
        ORDER BY A.post_date DESC
    ";
    $announcementDetails = $pdo->query($announcementQuery);
?>