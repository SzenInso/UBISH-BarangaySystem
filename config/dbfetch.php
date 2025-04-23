<?php 
    include '../../config/dbconfig.php';
    include '../../config/session.php';

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
        SELECT  A.announcement_id, A.title, A.body, A.privacy, A.category, A.author_id, A.post_date, 
                A.thumbnail, ATT.file_path, ATT.file_name, L.username, E.first_name, E.middle_name, E.last_name
        FROM announcements AS A
        JOIN login_details AS L ON A.author_id = L.user_id
        JOIN employee_details AS E ON L.emp_id = E.emp_id
        LEFT JOIN attachments AS ATT ON A.announcement_id = ATT.announcement_id
        ORDER BY A.post_date DESC
    ";
    $announcementDetails = $pdo->query($announcementQuery);

    // function that fetches single announcement to be deleted
    function toBeDeletedAnnouncement($pdo, $announcementID) {
        $toBeDeletedQuery = "
            SELECT  A.announcement_id, A.title, A.body, A.privacy, A.category, A.author_id, A.post_date, 
                    A.thumbnail, ATT.file_path, ATT.file_name, L.username, E.first_name, E.middle_name, E.last_name
            FROM announcements AS A
            JOIN login_details AS L ON A.author_id = L.user_id
            JOIN employee_details AS E ON L.emp_id = E.emp_id
            LEFT JOIN attachments AS ATT ON A.announcement_id = ATT.announcement_id
            WHERE A.announcement_id = :announcement_id
        ";
        $toBeDeletedDetails = $pdo->prepare($toBeDeletedQuery);
        $toBeDeletedDetails->execute([":announcement_id" => $announcementID]);
        $toBeDeleted = $toBeDeletedDetails->fetch();
        return $toBeDeleted;
    }
?>