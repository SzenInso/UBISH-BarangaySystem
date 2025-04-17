<?php 
    // fetches public announcement details in homepage w/o using $_SESSION
    include 'config/dbconfig.php';

    try {
        $publicAnnouncementQuery = "  SELECT A.*, L.username, E.first_name, E.middle_name, E.last_name FROM announcements AS A
                    JOIN login_details AS L ON A.author_id = L.user_id
                    JOIN employee_details AS E ON L.emp_id = E.emp_id
                    WHERE A.privacy = 'Public'
                    ORDER BY A.post_date DESC
        ";
        $publicAnnouncement = $pdo->query($publicAnnouncementQuery);
        $publicAnnouncementDetails = $publicAnnouncement->fetchAll();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "<script>console.log('Error fetching public announcements.');</script>";
        $publicAnnouncements = array();
    }
?>