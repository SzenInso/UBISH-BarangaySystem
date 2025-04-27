<?php 
    include '../../config/dbconfig.php';
    include '../../config/session.php';

    // fetches access control
    $accessQuery = "SELECT access_level FROM employee_details WHERE emp_id = :emp_id";
    $access = $pdo->prepare($accessQuery);
    $access->execute([":emp_id" => $_SESSION['emp_id']]);
    $accessLevel = $access->fetchColumn();

    // fetches registration requests
    $registrationQuery = "
        SELECT REG.*, EMP.*, LOG.* 
        FROM registration AS REG
        JOIN employee_registration AS EMP 
            ON REG.registration_emp_id = EMP.registration_emp_id
        JOIN login_registration AS LOG 
            ON REG.registration_login_id = LOG.registration_login_id
        WHERE REG.status = 'Pending'
        ORDER BY REG.request_date DESC
    ";
    $registration = $pdo->query($registrationQuery);

    // fetches profile edit requests
    $empUpdateQuery = "
        SELECT UPD.*, EMP.* FROM employee_update AS UPD
        JOIN employee_details AS EMP ON UPD.emp_id = EMP.emp_id
        WHERE UPD.update_status = 'Pending'
        ORDER BY UPD.update_request_date DESC
    ";
    $empUpdate = $pdo->query($empUpdateQuery);

    // fetches password !! SENSITIVE !!
    $passwordQuery = "SELECT password FROM login_details WHERE emp_id = :emp_id";
    $password = $pdo->prepare($passwordQuery);
    $password->execute([":emp_id" => $_SESSION['emp_id']]);
    $passwordHash = $password->fetchColumn();

    // fetches single employee details
    $empQuery = "
        SELECT EMP.*, LOG.username, LOG.email FROM employee_details AS EMP
        JOIN login_details AS LOG ON EMP.emp_id = LOG.emp_id
        WHERE EMP.emp_id = :emp_id
    ";
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